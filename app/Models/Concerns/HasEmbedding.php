<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;
use Laravel\Ai\Embeddings;

trait HasEmbedding
{
    /**
     * Generate and store the embedding vector for this model.
     *
     * @return array<float>
     */
    public function generateEmbedding(): array
    {
        $text = $this->toSearchableText();

        $response = Embeddings::for([$text])
            ->generate(provider: 'openai', model: 'text-embedding-nomic-embed-text-v1.5');

        $embedding = $response->first();

        $this->embedding = $embedding;
        $this->saveQuietly();

        return $embedding;
    }

    /**
     * Determine if this model has a stored embedding.
     */
    public function hasEmbedding(): bool
    {
        return ! is_null($this->embedding);
    }

    /**
     * Build a searchable text representation of this model.
     * Override this method in each model for entity-specific text.
     */
    abstract public function toSearchableText(): string;
}
