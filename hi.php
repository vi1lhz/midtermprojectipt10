<?php

namespace App\Publisher;

// Base Parent Class: Defines the Master Recipe
abstract class DocumentPublisher
{
    // The Template Method: Marked final so children cannot break the order
    final public function publishDocument(string $content): bool
    {
        $this->sanitizeInput($content);
        $formatted = $this->formatContent($content);
        
        if ($this->shouldApplyWatermark()) {
            $formatted = $this->applyWatermark($formatted);
        }
        
        $this->deliverDocument($formatted);
        $this->logPublication();
        
        return true;
    }

    private function sanitizeInput(string $raw): void
    {
        echo "[Shared Step] Cleaning raw input text...\n";
    }

    private function logPublication(): void
    {
        echo "[Shared Step] Saving record to audit log.\n";
    }

    // Abstract methods: Blank spots that child classes MUST fill in
    abstract protected function formatContent(string $raw): string;
    abstract protected function deliverDocument(string $formatted): void;

    // Hook: Optional step that defaults to false
    protected function shouldApplyWatermark(): bool
    {
        return false;
    }

    protected function applyWatermark(string $content): string
    {
        return $content . " [CONFIDENTIAL]";
    }
}

// Child Class 1: PDF Publisher
class PdfDocumentPublisher extends DocumentPublisher
{
    protected function formatContent(string $raw): string
    {
        return "<PDF-LAYOUT>" . $raw . "</PDF-LAYOUT>";
    }

    protected function deliverDocument(string $formatted): void
    {
        echo "[PDF Exporter] Writing PDF file: " . $formatted . "\n";
    }

    protected function shouldApplyWatermark(): bool
    {
        return true; // Overriding hook to add watermark
    }
}

// Child Class 2: Text Publisher
class TextDocumentPublisher extends DocumentPublisher
{
    protected function formatContent(string $raw): string
    {
        return strtoupper($raw);
    }

    protected function deliverDocument(string $formatted): void
    {
        echo "[Text Exporter] Saving TXT file: " . $formatted . "\n";
    }
}