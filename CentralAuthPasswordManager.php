<?php
/**
 * CentralAuthPasswordManager
 * ---------------------------
 * Handles password policy validation, secure updates,
 * and syncs with PASS_API_UPDATION API.
 */

class CentralAuthPasswordManager
{
    private $pdo;
    private $policy;

    public function __construct(PDO $pdo, array $policy = [])
    {
        $this->pdo = $pdo;

        // Default policy if not passed from config.php
        $this->policy = $policy ?: [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_number' => true,
            'require_special' => true,
            'disallow_username_password_same' => true,
            'disallow_last_n_passwords' => 3,
            // 'password_expiry_days' => 90
        ];
    }
    /**
     * Load current password policy from DB
     */
    // private function loadPolicy()
    // {
    //     $stmt = $this->pdo->query("SELECT * FROM password_policy ORDER BY id DESC LIMIT 1");
    //     $this->policy = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if (!$this->policy) {
    //         // Default fallback policy
    //         $this->policy = [
    //             'min_length' => 8,
    //             'require_uppercase' => true,
    //             'require_lowercase' => true,
    //             'require_number' => true,
    //             'require_special' => true,
    //             'disallow_last_n_passwords' => 3,
    //             'password_expiry_days' => 90
    //         ];
    //     }
    // }

    /**
     * Validate password against policy
     */
    public function isPasswordExpired($user)
    {
        var_dump("here");die;
        // fetch password_change date & role
        $stmt = $this->pdo->prepare("SELECT password_change, noc_roll 
                                    FROM central_auth 
                                    WHERE dhar_user = :user");
        $stmt->execute([':user' => $user]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return ['expired' => true, 'message' => 'User not found'];
        }

        $lastChange = $row['password_change'] ? new DateTime($row['password_change']) : null;
        $today = new DateTime();

        // decide expiry based on role
        $daysAllowed = $row['noc_roll'] === 'ADMIN'   // you can adjust condition
            ? $this->policy['expiry_days']['privileged']
            : $this->policy['expiry_days']['normal'];

        if (!$lastChange) {
            return ['expired' => true, 'message' => "Password never set"];
        }

        $daysPassed = $today->diff($lastChange)->days;

        if ($daysPassed >= $daysAllowed) {
            return ['expired' => true, 'message' => "Password expired ($daysPassed days old)"];
        }

        return ['expired' => false, 'message' => "Password valid ($daysPassed/$daysAllowed days)"];
    }
    public function validatePassword(string $password): array
    {
        $errors = [];

        if (strlen($password) < $this->policy['min_length']) {
            $errors[] = "Password must be at least {$this->policy['min_length']} characters.";
        }
        if ($this->policy['require_uppercase'] && !preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must include at least one uppercase letter.";
        }
        if ($this->policy['require_lowercase'] && !preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must include at least one lowercase letter.";
        }
        if ($this->policy['require_number'] && !preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must include at least one number.";
        }
        if ($this->policy['require_special'] && !preg_match('/[^a-zA-Z0-9]/', $password)) {
            $errors[] = "Password must include at least one special character.";
        }

        return $errors;
    }

    /**
     * Check if password was used recently
     */
    private function isPasswordReused(array $userRow, string $newPassword): bool
    {
        $previousPasswords = [
            $userRow['password'],
            $userRow['prev_password1'],
            $userRow['prev_password2'],
            $userRow['prev_password3']
        ];

        foreach ($previousPasswords as $oldHash) {
            if ($oldHash && password_verify($newPassword, $oldHash)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Update password locally and via PASS_API_UPDATION
     */
    public function updatePassword(string $dharUser, string $newPassword, string $mobile): array
    {
        // Fetch user
        $stmt = $this->pdo->prepare("SELECT * FROM central_auth WHERE dhar_user = :dhar_user");
        $stmt->execute([':dhar_user' => $dharUser]);
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userRow) {
            return ["result" => false, "msg" => "User not found."];
        }

        // Validate against policy
        $errors = $this->validatePassword($newPassword);
        if (!empty($errors)) {
            return ["result" => false, "msg" => implode(" ", $errors)];
        }

        // Check reuse
        if ($this->isPasswordReused($userRow, $newPassword)) {
            return ["result" => false, "msg" => "New password cannot match the last {$this->policy['disallow_last_n_passwords']} passwords."];
        }

        // Encrypt new password
        $options = ['cost' => 12];
        $newHash = password_hash($newPassword, PASSWORD_BCRYPT, $options);

        // Begin transaction
        $this->pdo->beginTransaction();

        try {
            // Rotate history
            $prev1 = $userRow['password'];
            $prev2 = $userRow['prev_password1'];
            $prev3 = $userRow['prev_password2'];

            // Update DB
            $stmt = $this->pdo->prepare("
                UPDATE central_auth
                SET password = :password,
                    prev_password1 = :prev1,
                    prev_password2 = :prev2,
                    prev_password3 = :prev3,
                    mobile = :mobile,
                    password_change = CURRENT_DATE,
                    password_change_flag = 1
                WHERE dhar_user = :dhar_user
            ");

            $stmt->execute([
                ':password'   => $newHash,
                ':prev1'      => $prev1,
                ':prev2'      => $prev2,
                ':prev3'      => $prev3,
                ':mobile'     => $mobile,
                ':dhar_user'  => $dharUser
            ]);

            // Call PASS_API_UPDATION
            // $curl = curl_init();
            // curl_setopt_array($curl, [
            //     CURLOPT_URL => PASS_API_UPDATION,
            //     CURLOPT_RETURNTRANSFER => true,
            //     CURLOPT_TIMEOUT => 60,
            //     CURLOPT_SSL_VERIFYHOST => 0,
            //     CURLOPT_SSL_VERIFYPEER => 0,
            //     CURLOPT_POST => true,
            //     CURLOPT_POSTFIELDS => [
            //         'uname'     => $userRow['dhar_user'],
            //         'cred'      => $newHash,
            //         'dist_code' => $userRow['dist_code'],
            //         'mobile'    => $mobile,
            //         'dhar_user' => $userRow['dhar_user'],
            //         'noc_user'  => $userRow['noc_user']
            //     ]
            // ]);

            // $response = curl_exec($curl);
            // if ($response === false) {
            //     throw new Exception("Curl error: " . curl_error($curl));
            // }
            // curl_close($curl);

            // $resp = json_decode($response);
            // if (!$resp || $resp[0]->responseType != 2) {
            //     throw new Exception("PASS_API_UPDATION failed: " . $response);
            // }

            // Commit transaction if API success
            $this->pdo->commit();
            return ["result" => true, "msg" => "Password updated successfully."];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ["result" => false, "msg" => "Password update failed: " . $e->getMessage()];
        }
    }
    public function getPasswordRulesHtml()
    {
        $rules = [];

        if (!empty($this->policy['min_length'])) {
            $rules[] = "Minimum length: <b>{$this->policy['min_length']}</b> characters";
        }
        if (!empty($this->policy['require_uppercase'])) {
            $rules[] = "Must contain at least one uppercase letter (A–Z)";
        }
        if (!empty($this->policy['require_lowercase'])) {
            $rules[] = "Must contain at least one lowercase letter (a–z)";
        }
        if (!empty($this->policy['require_number'])) {
            $rules[] = "Must contain at least one number (0–9)";
        }
        if (!empty($this->policy['require_special'])) {
            $rules[] = "Must contain at least one special character (!@#\$%^&*_- etc.)";
        }
        if (!empty($this->policy['disallow_username_password_same'])) {
            $rules[] = "Password cannot be the same as username";
        }
        if (!empty($this->policy['disallow_last_n_passwords'])) {
            $rules[] = "Cannot reuse the last <b>{$this->policy['disallow_last_n_passwords']}</b> passwords";
        }
        if (!empty($this->policy['password_expiry_days'])) {
            $rules[] = "Password will expire after <b>{$this->policy['password_expiry_days']}</b> days";
        }

        // Nicely formatted HTML list
        $html = '<div class="password-policy" style="background:#f8f9fa;padding:12px;border-radius:8px;font-size:14px;line-height:1.6;border:1px solid #ddd;">';
        $html .= '<h4 style="margin-top:0;color:#444;">Password Policy</h4>';
        $html .= '<ul style="margin:0;padding-left:20px;">';
        foreach ($rules as $rule) {
            $html .= "<li>{$rule}</li>";
        }
        $html .= '</ul></div>';

        return $html;
    }
    function getPolicy(){
        $policy = [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_number' => true,
            'require_special' => true,
            'disallow_username_password_same' => true,
            'disallow_last_n_passwords' => 3,
            'allowed_specials' => '_-.@',
            // 'password_expiry_days' => 90
        ];
        return $policy;
    }

}
