# Rule-Based Promotion Engine for B2B eCommerce Checkout

## Introduction

This project demonstrates a flexible, structured, and scalable **promotion engine** for a B2B eCommerce checkout system using a **custom PHP rule engine** and **json-rules-engine** in a full-stack Laravel + React architecture.

It supports structured conditions and actions for rule evaluation, enabling dynamic discounts based on customer or product attributes during checkout.


## Technical Stack

| Layer      | Technology                                         |
|------------|----------------------------------------------------|
| Backend    | Laravel 11 (PHP 8.3)                               |
| Database   | MySQL                                              |
| Frontend   | React (Vite) + Tailwind CSS                        |
| Rule Logic | `json-rules-engine` (Frontend)                     |
|            | Created my own custom PHP Rule Engine (Backend)    |
-------------------------------------------------------------------
💡 **Attention:** The `json-rules-engine` works with the React checkout page, but the **custom PHP rule engine** is currently testable only via **Postman**.


## Architecture

- **Laravel Backend API** exposes:
  - `/api/rules` – Manage promotion rules (CRUD).
  - `/api/evaluate` – Evaluate checkout lines using the rule engine.

- **MySQL Database** stores:
  - `products`, `categories`, `customers`, and `rules` in structured JSON.

- **Custom PHP Rule Engine**:
  - Parses JSON rule definitions.
  - Applies condition-action logic.
  - Supports `salience` (priority) and `stackable` flags.

- **json-rules-engine** (NPM):
  - Integrated in the React frontend.
  - Parses JSON rule definitions.
  - Applies condition-action logic.
  - Supports `salience` (priority) and `stackable` flags.


## Dual Rule Engine Strategy

### 1. json-rules-engine (Frontend)

- Used in the React frontend.
- Useful for building rule UIs and validating structures.

### 2. Custom PHP Rule Engine (Backend)

- Main processing logic lives in Laravel.
- Accepts `line` and `customer` context.
- Evaluates structured condition JSON.
- Executes structured actions like `applyPercent`, `applyFreeUnits`.
- Enforces:
  - `salience` ordering (lower runs first).
  - `stackable` control (to stop or continue evaluating rules).


## Data Entities

- **Categories**
- **Products**
- **Customers**
- **Rules**

Rules are stored in a **language-agnostic JSON format** that is parsed and processed in PHP.


## System Architecture Diagram

flowchart TD
    A[React Frontend - Vite] -->|REST API: fetch data| B[Laravel API]
    B -->|Reads data| C[MySQL Database]
    A -->|Sends data to| E[json-rules-engine - React]
    E -->|Returns evaluation result| A




