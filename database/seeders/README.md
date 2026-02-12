# Database Seeders - FocusOneX Archery

## Overview
Seeders untuk generate test data di FocusOneX Archery Club Management System.

## Available Seeders

### 1. AdminTestDataSeeder
Generate data testing untuk Admin dashboard.

**Data yang dibuat:**
- Admin user account
- Sample data untuk management

### 2. CoachTestDataSeeder  
Generate data testing untuk Coach dashboard.

**Data yang dibuat:**
- Coach user accounts
- Coach profiles
- Sample training data

### 3. MemberDashboardTestSeeder ⭐ (Recommended for Testing)
Generate comprehensive test data untuk Member dashboard dengan semua fitur.

**Data yang dibuat:**
- 1 User dengan role Member (login credential)
- 3 Member profiles:
  - Self member (Active)
  - Family member 1 (Active) 
  - Family member 2 (Pending approval)
- 2 Coaches dengan berbagai session times
- 3 Packages:
  - Basic Package (8 sessions)
  - Premium Package (12 sessions)
  - Professional Package (20 sessions)
- Multiple member packages:
  - 2 Active packages (self + family)
  - 1 Expired package (untuk testing history)
- 21 Training sessions:
  - 10 Past sessions (CLOSED status)
  - 1 Today session (OPEN status)
  - 10 Future sessions (OPEN status)
- Bookings & Attendance:
  - 10 Total bookings
  - 6 Present attendance
  - 2 Absent attendance
  - 2 Future bookings
- 8 Achievements:
  - 6 Achievements untuk main member
  - 2 Achievements untuk family member
  - Mix of medals, milestones, dan competitions

## How to Run

### Run All Seeders
```bash
php artisan db:seed
```

### Run Specific Seeder
```bash
# Member Dashboard Test Data (Recommended)
php artisan db:seed --class=MemberDashboardTestSeeder

# Admin Test Data
php artisan db:seed --class=AdminTestDataSeeder

# Coach Test Data
php artisan db:seed --class=CoachTestDataSeeder
```

### Fresh Migration + Seed
```bash
php artisan migrate:fresh --seed
```

## Test Credentials

### Member Dashboard Test Account
```
Email    : memberdashboard@test.com
Password : password123
Role     : Member
```

### Admin Test Account
```
Email    : admin@clubpanahan.com
Password : admin123
Role     : Admin
```

### Coach Test Accounts
```
Email    : budi.coach@clubpanahan.com
Password : coach123
Role     : Coach
```

## Member Dashboard Features to Test

Dengan MemberDashboardTestSeeder, Anda dapat testing semua fitur:

### ✅ Dashboard Overview
- Active package display dengan progress bar
- Quota statistics (Total, Used, Remaining sessions)
- Days remaining calculation
- Welcome banner dengan member status

### ✅ Attendance History
- Timeline view dengan 10 past attendances
- Present/Absent status indicators
- Date dan session information
- Visual timeline dengan color coding

### ✅ Achievements Showcase
- 8 Total achievements
- Mix of competition medals (🥇🥈)
- Milestone achievements (💯⭐)
- Recent achievements display

### ✅ Profile Management
- View dan edit profile information
- Member details (name, phone, email)
- Account status indicator
- Activity statistics

### ✅ Membership (Family Members)
- Self member (Active status)
- 2 Family members dengan berbagai status
- Register new member flow
- Buy package untuk members
- Status management (Active, Pending, Expired)

### ✅ Session Booking
- View available sessions (10 future sessions)
- Book sessions dengan berbagai coaches
- View booking history
- Cancel bookings
- Session time variations (morning, afternoon, evening)

### ✅ Package Purchase
- 3 Package options (Basic, Premium, Professional)
- Price comparison
- Session count details
- Duration information
- Purchase flow testing

## Data Characteristics

### Realistic Data
- Varied dates (past 10 days, today, next 10 days)
- Multiple coaches untuk session diversity
- Mix of attendance status (present majority, some absents)
- Active dan expired packages untuk complete testing
- Achievement dates spread over months

### Edge Cases Covered
- Expired package scenario
- Pending member approval
- Multiple family members
- Full booking history
- Various session times
- Different coach assignments

### Data Volume
- **Users**: 3 total (1 member, 2 coaches)
- **Members**: 3 (1 self, 2 family)
- **Packages**: 3 options
- **Member Packages**: 3 (2 active, 1 expired)
- **Session Times**: 5 different time slots
- **Training Sessions**: 21 sessions over 3 weeks
- **Bookings**: 10 total bookings
- **Attendances**: 8 attendance records
- **Achievements**: 8 achievements

## Cleanup

Seeder akan otomatis cleanup existing test data sebelum create new data untuk menghindari duplikasi.

## Tips for Testing

1. **Login dengan Member Account**
   ```
   URL: http://localhost/login
   Email: memberdashboard@test.com
   Password: password123
   ```

2. **Check Dashboard Overview**
   - Verify active package information
   - Check quota calculations
   - View attendance timeline

3. **Test Booking Flow**
   - Browse available sessions
   - Make new bookings
   - View booking history

4. **Test Membership Features**
   - View family members
   - Try registering new member
   - Check package purchase flow

5. **Profile Management**
   - Edit profile information
   - Update contact details
   - View activity stats

## Troubleshooting

### Database Connection Error
```bash
# Check database configuration
php artisan config:cache
php artisan config:clear
```

### Foreign Key Constraint Error
```bash
# Fresh migration needed
php artisan migrate:fresh --seed
```

### Seeder Already Run
Seeder can be run multiple times. It will cleanup existing test data automatically.

## Development Notes

- Seeder uses Factories untuk generate realistic data
- All timestamps are properly set untuk timeline accuracy
- Foreign keys are properly maintained
- Status enums are used correctly
- Data relationships are consistent

## Support

Untuk issues atau questions, hubungi development team atau check documentation.

---

**Last Updated**: February 2026
**Version**: 1.0.0
