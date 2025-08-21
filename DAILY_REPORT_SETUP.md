# Daily Pharmacy Report Setup Guide

## Overview
This system sends daily email reports to pharmacy owners at 22:00 (10 PM) every day, containing:
- Sales summary (revenue, cost, profit/loss, transactions)
- Stock status (out of stock, low stock, expired, good stock)
- Detailed tables for items requiring attention

## Files Created/Modified

1. **Command**: `app/Console/Commands/SendDailyPharmacyReport.php`
2. **Mailable**: `app/Mail/DailyPharmacyReport.php`
3. **Email Template**: `resources/views/emails/daily-pharmacy-report.blade.php`
4. **Scheduling**: `routes/console.php` (added daily scheduling at 22:00)

## Setup Instructions

### 1. Configure Email Settings
Update your `.env` file with proper email configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourpharmacy.com"
MAIL_FROM_NAME="Pharmacy Management System"
```

### 2. Set Up Cron Job
Add this line to your server's crontab to enable Laravel task scheduling:

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Test the Command

**Test for a specific pharmacy:**
```bash
php artisan pharmacy:send-daily-report --pharmacy_id=1
```

**Test for all pharmacies:**
```bash
php artisan pharmacy:send-daily-report
```

### 4. Multi-Tenant Features

The system automatically:
- Sends reports to all pharmacy owners
- Scopes all data by `pharmacy_id`
- Handles multiple pharmacies independently
- Includes only data relevant to each pharmacy

## Report Contents

### Sales Summary Table
- Total Revenue (KSh)
- Total Cost (KSh) 
- Profit/Loss (KSh) - colored green for profit, red for loss
- Total Transactions

### Stock Status Overview Table
- Out of Stock count and percentage
- Low Stock count and percentage  
- Expired items count and percentage
- Good Stock count and percentage

### Detailed Stock Tables (when applicable)
- **Out of Stock Items**: Item name, batch number, supplier, expiry date
- **Low Stock Items**: Item name, current stock, total stock, low stock %, batch number
- **Expired Items**: Item name, remaining quantity, expiry date, batch number, supplier

## Troubleshooting

1. **No emails sent**: Check email configuration in `.env`
2. **Cron not working**: Ensure cron job is properly set up
3. **Missing data**: Verify pharmacy_id relationships in database
4. **Permission issues**: Ensure proper file permissions for Laravel storage

## Manual Testing

You can manually trigger reports by running:
```bash
php artisan pharmacy:send-daily-report
```

The email template is responsive and includes proper styling for professional appearance.
