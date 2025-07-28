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


## Demo video (Test the `rules crud` - api/rules, and my `PHP Rule Engine` api/evaluate).

link: https://drive.google.com/file/d/1CrN99lANqHr12P3AtXRQQZefBgWIC0fA/view?usp=sharing


## System Architecture Diagram

```mermaid
flowchart TD
    A[React Frontend - Vite] --> B[Laravel API]
    B --> C[MySQL Database]
    C --> B
    B --> A
    A --> E[json-rules-engine]
    E --> A






