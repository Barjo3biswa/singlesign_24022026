<!-- Maintenance Notice Banner -->
<div class="maintenance-banner">
    <div class="maintenance-icon">
        &#9888;
    </div>
    <div class="maintenance-content">
        <h2>Scheduled Maintenance Alert</h2>
        <p>
            All <strong>Land Record</strong> and <strong>Registration</strong> services 
            will not be available on 
            <strong>28/11/2025</strong> from <strong>5:00 PM to 7:00 PM</strong> 
            due to maintenance work.
        </p>
        <p class="maintenance-note">
            We regret the inconvenience and appreciate your understanding.
        </p>
    </div>
</div>

<style>
    .maintenance-banner {
        max-width: 900px;
        margin: 20px auto;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-radius: 10px;
        background: linear-gradient(135deg, #fff7e6, #ffecd2);
        border: 1px solid #ffb84d;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: #663c00;
    }

    .maintenance-icon {
        font-size: 32px;
        flex-shrink: 0;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 2px solid #ff9900;
        box-shadow: 0 0 0 4px rgba(255, 153, 0, 0.15);
        animation: pulse 1.8s infinite;
    }

    .maintenance-content h2 {
        margin: 0 0 6px;
        font-size: 20px;
        font-weight: 700;
        color: #804000;
    }

    .maintenance-content p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
    }

    .maintenance-content strong {
        font-weight: 700;
    }

    .maintenance-note {
        margin-top: 4px;
        font-size: 12px;
        color: #8a5a10;
        opacity: 0.9;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 4px rgba(255, 153, 0, 0.15);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 8px rgba(255, 153, 0, 0.08);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 4px rgba(255, 153, 0, 0.15);
        }
    }

    /* Responsive */
    @media (max-width: 600px) {
        .maintenance-banner {
            flex-direction: column;
            text-align: left;
            padding: 16px;
        }

        .maintenance-icon {
            margin-bottom: 4px;
        }

        .maintenance-content h2 {
            font-size: 18px;
        }

        .maintenance-content p {
            font-size: 13px;
        }
    }
</style>

