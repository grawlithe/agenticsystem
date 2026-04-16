# AI Business Assistant Setup & Usage Guide

## Quick Start
Your AI Business Assistant is now fully configured and powered by locally-hosted Embeddings and dynamic SQL execution structures. 

```bash
php artisan chat
```

### What the AI Can Do
Unlike traditional dashboards, the AI is not restricted to pre-defined widgets. The LLM understands your database natively (via the `ReadBusinessEntities` tool) and executes raw operations using PostgreSQL vectors (`SearchEntities`) and structured SQL aggregations (`ExecuteSqlQuery`).

**Dynamic Explorations You Can Now Do:**
- "Count how many users from Europe purchased more than 3 products across all their orders combined." *(The LLM writes the complex JOINs and gives you the answer instantly)*
- "Search for products semantically matching 'heavy-duty garden utilities'." *(The LLM uses pgvector space mapping to find matching items)*
- "What does our revenue path look like over the next 90 days?" *(The LLM aggregates logic using `GetSalesProjection`)*

### Setup Dependencies
Make sure you have correctly initialized components before beginning:
1. **LM Studio**: Running Gemma 4 as the base, and Nomic Embed Text v1.5 loaded simultaneously for embeddings.
2. **PostgreSQL**: Set up tightly with standard relational schemas but equipped firmly with the `pgvector` extension.
3. **Vectors Configured**: Ran `php artisan embeddings:generate` at least once before chatting to compile the data.
