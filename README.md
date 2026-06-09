# Magic Survey — Magento 2 Module

A lightweight post-checkout survey module for Magento 2. Displays a survey on the order success (thank-you) page and stores customer responses against the order. Admins can manage surveys, questions, and view detailed response reports from the backend.

---

## Features

- Create and manage multiple surveys from the admin panel
- Four question types: **Radio**, **Checkbox**, **Dropdown**, **Text**
- Schedule surveys by start and end date
- Enable / disable surveys independently
- Survey is automatically shown on the checkout success page for active surveys
- Captures customer email and order reference for every response (works for both guests and logged-in customers)
- Admin results grid with a detailed per-response view
- Full ACL support for admin permissions
- REST API for fetching surveys, questions, and submitting responses
- GraphQL API with queries and mutation

---

## Requirements

| Dependency | Version |
|---|---|
| Magento Open Source / Commerce | 2.4.x |
| PHP | 8.1+ |
| MySQL | 5.7+ / 8.0+ |

---

## Installation

### 1. Copy the module files

Place the module directory into your Magento installation:

```
app/code/Magic/Survey/
```

### 2. Enable the module

```bash
bin/magento module:enable Magic_Survey
```

### 3. Run setup upgrade

Creates the required database tables:

```bash
bin/magento setup:upgrade
```

### 4. Compile dependency injection

```bash
bin/magento setup:di:compile
```

### 5. Deploy static content (production mode only)

```bash
bin/magento setup:static-content:deploy
```

### 6. Flush the cache

```bash
bin/magento cache:flush
```

---

## Configuration

No system configuration is required. All management is done from the admin panel.

### Admin Panel

Navigate to **Surveys** in the admin sidebar.

#### Managing Surveys

1. Go to **Surveys → Manage Surveys**
2. Click **Add New Survey** to create a survey
3. Fill in:
   - **Title** *(required)* — displayed as the survey heading on the frontend
   - **Description** — optional subtitle shown below the title
   - **Status** — set to **Enabled** to make the survey active
   - **Start Date** — leave blank to make the survey available immediately
   - **End Date** — leave blank for no expiry
4. Click **Save Survey**

#### Adding Questions

After saving a survey, a **Questions** section appears at the bottom of the edit page.

1. Click **Add New Question**
2. Fill in:
   - **Question Text** *(required)*
   - **Question Type** — Radio, Checkbox, Dropdown, or Text
   - **Required** — toggle on to force an answer before submission
   - **Sort Order** — controls display order (lower = first)
3. For Radio / Checkbox / Dropdown types, add answer options in the **Answer Options** section using **Add Option**
4. Click **Save Question**
5. Repeat for each question

#### Viewing Responses

1. Go to **Surveys → Survey Results**
2. The grid lists all submissions with customer email, order reference, and submission date
3. Click **View** on any row to see the full response with answers per question

---

## REST API

All endpoints are publicly accessible (no authentication token required) and follow the Magento REST API convention: `/rest/<store_code>/V1/magic-survey/...`

Replace `<store_code>` with `default` or your store code, e.g. `/rest/default/V1/magic-survey/active`.

---

### 1. Get Active Survey

Returns the first currently enabled survey whose date range covers today.

```
GET /rest/V1/magic-survey/active
```

**Response**

```json
{
  "survey_id": 1,
  "title": "Tell us your Journey",
  "description": "We'd love to hear about your experience.",
  "status": 1,
  "start_date": "2026-06-01",
  "end_date": "2026-12-31",
  "created_at": "2026-06-09 06:00:00",
  "updated_at": "2026-06-09 06:00:00"
}
```

**Error (no active survey)**

```json
{
  "message": "No active survey found."
}
```

---

### 2. Get Questions for a Survey

Returns all questions for the given survey, ordered by sort order. Radio, Checkbox, and Dropdown questions include a nested `options` array.

```
GET /rest/V1/magic-survey/:surveyId/questions
```

**Path Parameters**

| Parameter  | Type | Description       |
|------------|------|-------------------|
| `surveyId` | int  | ID of the survey  |

**Response**

```json
[
  {
    "question_id": 1,
    "survey_id": 1,
    "title": "How easy was it to find the product(s) you wanted today?",
    "type": "radio",
    "is_required": 1,
    "sort_order": 0,
    "options": [
      { "option_id": 1, "question_id": 1, "label": "Very easy",       "sort_order": 0 },
      { "option_id": 2, "question_id": 1, "label": "Easy",            "sort_order": 1 },
      { "option_id": 3, "question_id": 1, "label": "Neutral",         "sort_order": 2 },
      { "option_id": 4, "question_id": 1, "label": "Difficult",       "sort_order": 3 },
      { "option_id": 5, "question_id": 1, "label": "Very difficult",  "sort_order": 4 }
    ]
  },
  {
    "question_id": 5,
    "survey_id": 1,
    "title": "What did you like most about your experience today?",
    "type": "text",
    "is_required": 0,
    "sort_order": 4,
    "options": []
  }
]
```

**Error (survey not found)**

```json
{
  "message": "Survey with ID \"%1\" does not exist.",
  "parameters": ["1"]
}
```

---

### 3. Submit Survey Response

Saves a customer's answers for a survey. Returns `true` on success.

```
POST /rest/V1/magic-survey/submit
Content-Type: application/json
```

**Request Body**

| Field           | Type   | Required | Description                                              |
|-----------------|--------|----------|----------------------------------------------------------|
| `surveyId`      | int    | Yes      | ID of the survey being answered                          |
| `orderId`       | int    | Yes      | Order ID associated with this response                   |
| `customerEmail` | string | Yes      | Email address of the respondent                          |
| `answers`       | array  | Yes      | Array of answer objects (see below)                      |

**Answer object**

| Field        | Type        | Description                                                    |
|--------------|-------------|----------------------------------------------------------------|
| `question_id`| int         | ID of the question being answered                              |
| `option_id`  | int\|null   | Selected option ID (for radio, checkbox, dropdown questions)   |
| `answer_text`| string\|null| Free-text answer (for text questions)                          |

**Example Request**

```json
{
  "surveyId": 1,
  "orderId": 192,
  "customerEmail": "customer@example.com",
  "answers": [
    { "question_id": 1, "option_id": 2,  "answer_text": null },
    { "question_id": 2, "option_id": 8,  "answer_text": null },
    { "question_id": 3, "option_id": 13, "answer_text": null },
    { "question_id": 4, "option_id": null, "answer_text": "Great product range and fast checkout!" },
    { "question_id": 5, "option_id": null, "answer_text": "Search could be improved." }
  ]
}
```

**Success Response**

```json
true
```

**Error Response**

```json
{
  "message": "At least one answer is required."
}
```

---

## GraphQL API

The module exposes a GraphQL schema with two queries and one mutation. Send all requests to your store's GraphQL endpoint: `/graphql`

---

### Query: magicActiveSurvey

Returns the currently active survey.

```graphql
query {
  magicActiveSurvey {
    survey_id
    title
    description
    status
    start_date
    end_date
  }
}
```

**Response**

```json
{
  "data": {
    "magicActiveSurvey": {
      "survey_id": 1,
      "title": "Tell us your Journey",
      "description": "We'd love to hear about your experience.",
      "status": 1,
      "start_date": "2026-06-01",
      "end_date": "2026-12-31"
    }
  }
}
```

---

### Query: magicSurveyQuestions

Returns all questions (with nested answer options) for a given survey.

```graphql
query {
  magicSurveyQuestions(survey_id: 1) {
    question_id
    title
    type
    is_required
    sort_order
    options {
      option_id
      label
      sort_order
    }
  }
}
```

**Response**

```json
{
  "data": {
    "magicSurveyQuestions": [
      {
        "question_id": 1,
        "title": "How easy was it to find the product(s) you wanted today?",
        "type": "radio",
        "is_required": 1,
        "sort_order": 0,
        "options": [
          { "option_id": 1, "label": "Very easy",      "sort_order": 0 },
          { "option_id": 2, "label": "Easy",           "sort_order": 1 },
          { "option_id": 3, "label": "Neutral",        "sort_order": 2 },
          { "option_id": 4, "label": "Difficult",      "sort_order": 3 },
          { "option_id": 5, "label": "Very difficult", "sort_order": 4 }
        ]
      },
      {
        "question_id": 5,
        "title": "What did you like most about your experience today?",
        "type": "text",
        "is_required": 0,
        "sort_order": 4,
        "options": []
      }
    ]
  }
}
```

---

### Mutation: magicSubmitSurvey

Submits a customer's survey response. Returns `true` on success.

```graphql
mutation {
  magicSubmitSurvey(input: {
    survey_id: 1
    order_id: 192
    customer_email: "customer@example.com"
    answers: [
      { question_id: 1, option_id: 2,  answer_text: null }
      { question_id: 2, option_id: 8,  answer_text: null }
      { question_id: 3, option_id: 13, answer_text: null }
      { question_id: 4, option_id: null, answer_text: "Great product range!" }
      { question_id: 5, option_id: null, answer_text: "Search could be improved." }
    ]
  })
}
```

**Response**

```json
{
  "data": {
    "magicSubmitSurvey": true
  }
}
```

**GraphQL Schema File**: `etc/schema.graphqls`

**Resolvers**:

| Resolver class | Purpose |
|---|---|
| `Model/Resolver/ActiveSurvey.php` | Returns first active survey matching today's date |
| `Model/Resolver/SurveyQuestions.php` | Returns questions + options for a survey |
| `Model/Resolver/SubmitSurvey.php` | Saves a survey response and all answers |

---

## How It Works

1. When a customer completes an order, the `checkout_onepage_controller_success_action` event fires
2. The module looks for the first active survey whose date range covers today
3. If found, the survey is injected into the order success page via a block observer
4. The customer fills in the survey and submits via AJAX — no page reload
5. The response and all per-question answers are saved to the database linked to the order

---

## Uninstall

```bash
bin/magento module:disable Magic_Survey
bin/magento setup:upgrade
```

To also remove the database tables:

```sql
DROP TABLE IF EXISTS magic_survey_response_detail;
DROP TABLE IF EXISTS magic_survey_response;
DROP TABLE IF EXISTS magic_survey_answer_option;
DROP TABLE IF EXISTS magic_survey_question;
DROP TABLE IF EXISTS magic_survey;
```

Then delete the module directory:

```bash
rm -rf app/code/Magic/Survey
```
