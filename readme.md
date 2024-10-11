# PILAR (Pantauan IP Laporan Real-Time)
![WhatsApp Image 2024-10-11 at 16 27 49](https://github.com/user-attachments/assets/dc2ecfa3-d40e-48a3-b025-eb732dea4696)



This script is designed to monitor the status of multiple IP addresses, especially for SDWAN devices. It uses HTTPS requests to check if the IP is reachable, and integrates with Telegram to send alerts when an IP is down. Additionally, it provides the option to add new IPs dynamically through a form, with their locations, and saves them in session.

## Features

1. **IP Status Checker**: Checks the status of predefined SDWAN IPs.
2. **HTTPS Request**: Uses curl to make HTTPS requests to the IP addresses.
3. **Auto-Refresh**: The page automatically refreshes every 5 seconds, but you can start/stop auto-refresh using the provided buttons.
4. **Add IP Functionality**: You can add new IP addresses through a form, specifying the location and IP. Valid IPs are stored in session.
5. **Session Management**: Newly added IPs are stored in the session and merged with default IPs.
6. **Telegram Notification**: If an IP goes down, a notification is sent to a specified Telegram channel, including the IP, location, and timestamp.
7. **Form Validation**: IPs are validated before being added. Invalid IPs will not be stored.
8. **Responsive Design**: The page is styled with Tailwind CSS, ensuring it's responsive and user-friendly.

## How to Use

1. **Install the script**:
   - Upload the script to your server or local machine.
   - Ensure PHP is installed and running.

2. **Configure Telegram**:
   - Set your Telegram Bot token and Chat ID in the script:
     ```php
     $telegram_token = 'YOUR_TELEGRAM_BOT_TOKEN';
     $telegram_chat_id = 'YOUR_CHAT_ID';
     ```

3. **Start the Script**:
   - Access the script via a browser.
   - The IP status will automatically be checked and displayed on the page.
   - Add new IP addresses through the form.

4. **Auto-Refresh**:
   - Use the "Start Auto Refresh" button to begin refreshing the page every 5 seconds.
   - Use the "Stop Auto Refresh" button to pause the refreshing process.

## Requirements

- PHP 7.0 or higher
- Tailwind CSS (loaded via CDN)
- Curl enabled in PHP

## Important Notes

- **Session Storage**: Newly added IP addresses are stored in the session. Once the session expires or is cleared, those IPs will be lost.
- **Telegram Alerts**: The script will send alerts to the configured Telegram bot when any of the monitored IPs is down.
- **HTTPS**: The script uses HTTPS requests to check the IP status, ensuring secure communication.

## Example IPs

The following IPs are pre-configured in the script:

| Location          | IP Address    |
|-------------------|---------------|
| JAKTIM            | 20.31.123.1   |
| JAKBAR            | 20.31.124.1   |
| JAKUT             | 20.31.125.1   |
| JAKSEL            | 20.31.126.1   |
| JAKPUS            | 20.31.127.1   |
| KEP 1000          | 20.31.128.1   |
| BALAIKOTA         | 20.31.129.1   |
| WALIKOTA          | 20.31.130.1   |

## License

This project is licensed under the MIT License.
