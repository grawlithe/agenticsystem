# How to Setup the AI Business Intelligence System

Welcome! This guide will walk you through the setup process of your AI Business Assistant, including configuring your local Large Language Model via LM Studio with Gemma 4, and troubleshooting common issues you may encounter in the development process.

---

## 🛠 Project Initialization

### 1. Install Dependencies
Ensure you have PHP and Composer installed, then install the project dependencies:
```bash
composer install
```

### 2. Environment Configuration
Copy the example environment file and generate your application key:
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Setup

> [!IMPORTANT]
> This project requires an **embedding model** (for semantic search capabilities) and a **Vector Database**. 
> You must use **PostgreSQL** with the **`pgvector`** extension installed and enabled.

Configure your PostgreSQL database credentials in the `.env` file (ensure `DB_CONNECTION=pgsql`), then run the database migrations and seeders to populate the database with sample business data:
```bash
php artisan migrate
php artisan db:seed
```

### 4. Create Database Embeddings
Once your database is seeded and LM Studio actively holds the **Nomic Embed Text** model running on the local server, generate the database vectors so the AI can execute fuzzy semantic matching:
```bash
php artisan embeddings:generate
```

---

## 🧠 Setting up LM Studio and Gemma 4

To run the AI agent entirely locally, we use LM Studio configured to act as an OpenAI-compatible API endpoint powering the system with the **Gemma 4** model.

### 1. Configure LM Studio Base & Embeddings
1. Download and install [LM Studio](https://lmstudio.ai/).
2. In LM Studio, search for and download the **Gemma 4** (e.g. `google/gemma-4-e4b`) model for conversation generation.
3. Also search for and download the **Nomic Embed Text version 1.5** model (`nomic-embed-text-v1.5`) for semantic database search embeddings.
4. Navigate to the **Local Server** tab in LM Studio, and load **both** models if your hardware allows multi-model loading (or prepare to swap them depending on the task).
5. In the **Prompt Format** options for the conversational model, ensure you configure the following Jinja template so the agent's system prompts work correctly with Gemma 4:
   ```jinja
   {{ bos_token }}{% if messages[0]['role'] == 'system' %}{{ '<|start_of_turn|>system\n' + messages[0]['content'] + '<|end_of_turn|>\n' }}{% set loop_messages = messages[1:] %}{% else %}{% set loop_messages = messages %}{% endif %}{% for message in loop_messages %}{% if message['role'] == 'user' %}{{ '<|start_of_turn|>user\n' + message['content'] + '<|end_of_turn|>\n' }}{% elif message['role'] == 'assistant' %}{{ '<|start_of_turn|>model\n' + message['content'] + '<|end_of_turn|>\n' }}{% endif %}{% endfor %}{{ '<|start_of_turn|>model\n' }}
   ```
6. Start the local server. Ensure it exposes the endpoint at `http://localhost:1234/v1`.

### 2. Configure Laravel Environment
Update your `.env` file to point the AI provider to your local LM studio server:

```properties
OPENAI_API_KEY=lm-studio
OPENAI_BASE_URL=http://localhost:1234/v1
```

### 3. Configure AI Service
In `config/ai.php`, ensure the default provider is set to `openai`, and that the `openai` driver uses your local Gemma 4 model:

```php
    'default' => 'openai',
    'default_for_images' => 'openai',
    // ...
    'providers' => [
        // ...
        'openai' => [
            'driver' => 'openai',
            'key' => env('OPENAI_API_KEY'),
            'url' => env('OPENAI_BASE_URL'),
            'model' => 'google/gemma-4-e4b',
        ],
    ],
```

---

## ⚠️ Known Issues and Troubleshooting

If you are developing complex Agentic flows, you might encounter issues due to how JSON Schemas and underlying HTTP requests are structured. Here is how to fix them:

### 1. Schema Validation Error (JSON Payload)
**Error:** `Gemini Error [400]: INVALID_ARGUMENT - Invalid JSON payload received. Unknown name "type"... Proto field is not repeating, cannot start list.`

**Cause:** When writing schemas for your tools, using methods like `->nullable()` generates an array format under the hood (e.g. `["type": ["string", "null"]]`). The Gemini API specification (which might be strictly enforced depending on your parser) expects `type` to be a strictly single string configuration.

**Fix:** Avoid using `nullable()`. Provide a default behavior via a description text and let the AI know it's optional:
```php
// ❌ Avoid this:
'status_filter' => $schema->string()->nullable(),

// ✅ Do this instead:
'status_filter' => $schema->string()->description('Optional status filter'),
```

### 2. LM Studio Streaming and Strict Tools Error
**Error:** Chat crashes mid-stream or LM Studio logs complain about `[WARN] At least one tool has 'strict' set to true. This setting is not yet supported and will be ignored.` and the application stops awaiting the response.

**Cause:** While LM Studio is great for local generation, its OpenAI-compatibility layer for complex tools handling via event streaming might sometimes fail or not be fully supported by the specific model version (like Gemma 4).

**Fix:** Switch the implementation from a `.stream()` method to a synchronous `.prompt()` return in your invocation command so it captures the full valid JSON response before attempting to parse tool payloads.

In your `app/Console/Commands/Test.php` (or relevant implementation):
```php
// ❌ Avoid this:
$assistant->stream($input)->each(...);

// ✅ Do this instead:
$response = task('Thinking...', function () use ($assistant, $input) {
    return $assistant->prompt($input);
});
$this->info($response->text);
```

### 3. Missing `get`/`input` Method on Requests
**Error:** `BadMethodCallException: Method Laravel\Ai\Tools\Request::get does not exist.`

**Cause:** The `Laravel\Ai\Tools\Request` class does not inherit the standard HTTP `Illuminate\Http\Request` methods (like `get()` or `input()`).

**Fix:** The AI Request tool implements `ArrayAccess`. Access tool parameters natively using array-index notation:

```php
// ❌ Avoid this:
$limit = $request->get('limit', 5);
$status = $request->input('status_filter');

// ✅ Do this instead:
$limit = $request['limit'] ?? 5;
$status = $request['status_filter'] ?? null;
```

---

## 🚀 Running the System

After you've set up everything, started LM studio, and mitigated the above errors, run the CLI agent interface:

```bash
php artisan chat
```

You can now ask things like:
- *"What's the current state of my business?"*
- *"Project sales for the next 30 days"*
- *"Which products are low on stock?"*
