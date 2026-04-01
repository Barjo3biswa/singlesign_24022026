<?php
// Sets up jQuery to automatically include the CSRF token on all AJAX requests
?>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?= $_SESSION['csrf_token'] ?? '' ?>'
            }
        });
    });
</script>
