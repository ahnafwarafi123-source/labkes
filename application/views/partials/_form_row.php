<div class="mb-4">
  <?php if (isset($label)): ?>
    <label for="<?= $id ?? $name ?? '' ?>" class="form-label fw-semibold text-premium"><?= htmlspecialchars($label) ?></label>
  <?php endif; ?>
  
  <?php if (isset($input)): ?>
    <?= $input ?>
  <?php else: ?>
    <input type="<?= $type ?? 'text' ?>" 
           class="form-control input-premium <?= $class ?? '' ?>" 
           id="<?= $id ?? $name ?? '' ?>" 
           name="<?= $name ?? '' ?>"
           value="<?= $value ?? set_value($name ?? '') ?>"
           placeholder="<?= $placeholder ?? '' ?>"
           <?= $attributes ?? '' ?>
           required="<?= $required ?? 'false' ?>">
  <?php endif; ?>
  
  <?php if (isset($help)): ?>
    <div class="form-text text-muted mt-1"><?= htmlspecialchars($help) ?></div>
  <?php endif; ?>
  
  <?php if (isset($error)): ?>
    <div class="invalid-feedback d-block"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
</div>

<style>
/* Premium Form Row Styles */
.input-premium {
  border: 2px solid transparent;
  border-radius: var(--border-radius);
  background: var(--glass-bg);
  /* backdrop-filter: blur(10px); disabled */
  /* -webkit-backdrop-filter: blur(10px); disabled */
  transition: var(--transition-luxury);
  padding: 0.875rem 1.125rem;
  font-size: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.input-premium:focus {
  border-color: transparent;
  box-shadow: 0 0 0 0.25rem rgba(102,126,234,0.25), var(--premium-shadow-light);
  background: rgba(255,255,255,0.95);
  transform: translateY(-1px);
}

.input-premium.is-invalid {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.25rem rgba(220,53,69,0.25);
}

.text-premium {
  color: var(--text-primary);
  font-weight: 600;
  letter-spacing: 0.25px;
}

.form-label {
  margin-bottom: 0.625rem;
}
</style>

