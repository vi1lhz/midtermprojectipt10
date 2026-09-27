# Midterm Project for IPT10 Jiyan Sunga
 PHP Template Method Design Pattern Implementation

This repository demonstrates the **Template Method Design Pattern** implemented in modern PHP (8.1+). The pattern provides a clean, object-oriented way to define the skeleton of an algorithm in a base class while allowing subclasses to override specific steps without changing the overall workflow structure (Gamma et al., 1994).

---

## Architecture & Code Breakdown

The implementation consists of an abstract base class (`DocumentPublisher`) that enforces a strict processing order, alongside concrete child classes (`PdfDocumentPublisher` and `TextDocumentPublisher`) that implement custom behaviors.

### 1. The Abstract Base Class (`DocumentPublisher`)
The base class acts as the "master template." It controls the entire execution pipeline through a `final` method, preventing child classes from altering the core sequence.

* **The Template Method (`final public function publishDocument`)**: Coordinates the execution sequence:
  1. Sanitizes input (`sanitizeInput`).
  2. Formats content (`formatContent` — abstract).
  3. Checks and applies an optional watermark (`shouldApplyWatermark` & `applyWatermark`).
  4. Delivers the document (`deliverDocument` — abstract).
  5. Logs the publication (`logPublication`).
* **Invariable Steps (`private`)**: Common utilities like `sanitizeInput()` and `logPublication()` are implemented entirely in the base class so they never need to be rewritten.
* **Primitive Operations (`abstract protected`)**: Methods like `formatContent()` and `deliverDocument()` have no body in the parent class. Child classes **must** implement them.
* **Hooks (`protected`)**: Methods like `shouldApplyWatermark()` provide default optional behavior (returning `false`). Subclasses can choose to override them or leave them as-is (Freeman & Robson, 2020).

### 2. Concrete Subclasses
* **`PdfDocumentPublisher`**: Extends the base class to handle PDF-specific formatting (`<PDF-LAYOUT>`), custom delivery handling, and overrides the hook to enable watermarking (`return true;`).
* **`TextDocumentPublisher`**: Extends the base class to provide basic uppercase plain-text transformation and standard text delivery without a watermark.

---

## How It Works (Execution Flow)

When a client script calls `publishDocument()` on either publisher, the underlying execution follows this strict sequence:

```mermaid
sequenceDiagram
    autonumber
    actor Client
    participant Child as PdfDocumentPublisher
    participant Parent as DocumentPublisher (Base)

    Client->>Child: publishDocument(content)
    Child->>Parent: (Inherited template method call)
    Parent->>Parent: sanitizeInput(raw) [Shared]
    Parent->>Child: formatContent(raw) [Primitive]
    Child-->>Parent: returns formatted layout
    Parent->>Child: shouldApplyWatermark() [Hook]
    Child-->>Parent: returns true
    Parent->>Parent: applyWatermark(content) [Shared]
    Parent->>Child: deliverDocument(formatted) [Primitive]
    Child-->>Parent: file written
    Parent->>Parent: logPublication() [Shared]
    Parent-->>Client: returns true