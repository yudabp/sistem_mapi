# Task List - New Feature Implementation & Bug Fixes

## Overview
This document outlines the tasks required to implement new features and fix outstanding bugs as described in `new_feature.md`.

---

## 1. High Priority Bug Fixes

### 1.1 Photo Modal Functionality
- **Task 1.1.1**: Investigate and fix the photo/proof modal.
  - [ ] **Description**: The modal for displaying photos and other proof is not working correctly.
  - [ ] **Acceptance Criteria**:
    - The modal must open when a "View Photo" or similar button is clicked.
    - The correct photo or document must be displayed within the modal.
    - The modal must be closable.
  - [ ] **Priority**: High

### 1.2 General UI/UX
- **Task 1.2.1**: Disable Alpine.js debugger overlay.
  - [ ] **Description**: A debugger overlay related to Alpine.js flashes on the screen on every page load or refresh. This needs to be disabled in the production environment.
  - [ ] **Acceptance Criteria**:
    - No debugger overlay should be visible to the end-user.
  - [ ] **Priority**: Medium

---

## 2. Feature Enhancement: Keuangan Perusahaan (Company Finance)

### 2.1 Implement Special Expense to Cash Book Integration
- **Task 2.1.1**: Refine Company Finance Module.
  - [ ] **Description**: Implement a feature where a special category of "Expense" in the Company Finance module automatically creates an "Income" entry in the Buku Kas (Cash Book).
  - [ ] **Requirements**:
    - The Company Finance module must retain its existing separate "Income" and "Expense" functionalities.
    - Create a new, specific expense type/category (e.g., "Expense for Cash Book").
    - When an expense of this new type is recorded, an income entry should be automatically generated in the Cash Book.
  - [ ] **Sub-tasks**:
    - [ ] Modify the backend logic to handle this new expense type.
    - [ ] Update the UI to allow users to select this new expense type.
    - [ ] Ensure the process is atomic (e.g., use database transactions).
    - [ ] Add clear labels or tooltips in the UI to explain the functionality of this special expense type.
  - [ ] **Priority**: High

---

## Notes
- This task list replaces the previous version and is based on the latest `new_feature.md`.
