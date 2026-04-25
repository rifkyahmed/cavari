# Comprehensive QA Manual Testing Plan

This document contains two tables for manual Quality Assurance (QA) testing. We have divided the testing workload into two sections: one for the AI to test via browser automation, and one for you (the User) to process manually.

---

## 🤖 AI Manual Testing Table (Automated Browser Testing)
These are the visual and functional tests that I (the AI) am running manually on the frontend right now.

| Feature / Flow | Test Description | Expected Result | Status | Notes |
| :--- | :--- | :--- | :--- | :--- |
| **Homepage UI/UX** | Verify homepage loads, correct styling, hero section, and categories. | Page loads correctly without visual bugs; animations work. | **Passed** | Luxury UI renders perfectly. Scroll animations and "Discover" button work. |
| **Product Listing** | Browse products, check filters, check image loading. | Products display properly, layout is neat. | **Passed** | Treasury grid loads with correct fonts and layout. |
| **Product Details** | Click a product, verify details, price, gallery, and "Add to Cart" button. | Product page shows correct info, layout is premium. | **Passed** | Validated on "Midnight Star". Price ($9,500) and details render correctly. |
| **Cart Operations** | Add to cart, view cart modal/page, check totals. | Item appears in cart, totals update correctly. | **Passed** | AJAX add to cart functions instantly; header count updates seamlessly. |
| **User Sign Up/Login** | Open the authentication modal, check tabs, styling. | Modal opens smoothly, tabs switch, validation works. | **Passed** | Glassmorphism modal overlay opens via navbar profile icon correctly. |
| **Contact Form** | Navigate to Contact page and verify form. | Form is visible and can be interacted with. | **Passed** | Reached via navbar. Form layout is clean and responsive. |

---

## 👤 User Manual Testing Table
These are the tests assigned to you. They involve backend integrations, actual emails, or Stripe configurations. You can update the status of these as you test them.

| Feature / Flow | Test Description | Expected Result | Status | Notes |
| :--- | :--- | :--- | :--- | :--- |
| **Admin Login** | Navigate to `/admin`, enter admin credentials (`admin@example.com`). | Successfully redirects to Admin Dashboard. | Pending | |
| **Admin Dashboard** | Check revenue stats, out-of-stock items, recent orders. | Data matches database accurately. | Pending | |
| **Admin: Add Product** | Create a new test product with an image, price, and stock. | Product is created and appears on the shop page. | Pending | |
| **Checkout Payment** | Complete a purchase using Stripe test cards on the checkout page. | Payment succeeds, redirected to success page, order in DB. | Pending | |
| **Custom Order Flow** | Admin creates a custom order and generates a link; User pays. | Link works, payment goes through, status updates to Paid. | Pending | |
| **Password Reset** | Request a password reset code. | Email arrives with 6-digit code, code works to reset password. | Pending | |
| **Profile Updates** | User updates profile info and saves a shipping address. | Information correctly saves to the database. | Pending | |
