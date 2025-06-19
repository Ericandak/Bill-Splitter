# Bill Splitter

A Laravel-based web application that helps friends split bills and track shared expenses easily. Perfect for trips, roommates, or any shared expenses.

## Features

### 🎯 Core Features
- **Bill Management**: Create and manage shared bills
- **Expense Tracking**: Add expenses with custom splits
- **Smart Calculations**: Automatically calculates who owes whom
- **Friend System**: Add friends and manage friend requests
- **Real-time Balance**: See updated balances as expenses are added

### 💡 Key Highlights
- User Authentication & Authorization
- Friend Request System
- Responsive Design
- Intuitive User Interface
- Secure Data Handling

## Tech Stack

- **Framework**: Laravel 10.x
- **Database**: MySQL
- **Frontend**: Bootstrap 5
- **Authentication**: Laravel Breeze
- **Icons**: Font Awesome
- **JavaScript**: Vanilla JS



## Database Structure

```plaintext
users
 - id
 - name
 - email
 - password

bills
 - id
 - name
 - created_by
 - status
 - created_at
 - updated_at

expenses
 - id
 - bill_id
 - title
 - amount
 - paid_by
 - created_at
 - updated_at

expense_shares
 - id
 - expense_id
 - user_id

friendships
 - id
 - user_id
 - friend_id
 - created_at
 - updated_at
```

## Calculation Logic

The app uses a sophisticated algorithm to calculate splits:
1. Tracks who paid what amount
2. Calculates individual shares
3. Computes net balances
4. Determines optimal settlement plan


## Contact

Erick James - erickjames4512@gmail.com


---

Made by Erick_James
