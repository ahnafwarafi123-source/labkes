// Premium JS Features for KESMAS
// Dark Mode Toggle, Lazy Loading, Animations

(function() {
  'use strict';


  // Lazy Load Images
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          img.src = img.dataset.src;
          img.classList.remove('lazy');
          img.classList.add('fade-in');
          observer.unobserve(img);
        }
      });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
      imageObserver.observe(img);
    });
  }

  // Smooth Scroll Anchors
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', (e) => {
      e.preventDefault();
      const target = document.querySelector(anchor.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Enhanced Animations on Scroll
  if ('IntersectionObserver' in window) {
    const animObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-in-view');
          animObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
      animObserver.observe(el);
    });
  }

  // Loading Skeletons
  function replaceSkeleton(container) {
    if (container.classList.contains('skeleton-loaded')) return;
    container.classList.remove('loading-skeleton');
    container.classList.add('skeleton-loaded', 'fade-in');
  }

  // Copy to Clipboard
  document.querySelectorAll('[data-copy]').forEach(btn => {
    btn.addEventListener('click', () => {
      const text = btn.getAttribute('data-copy');
      navigator.clipboard.writeText(text).then(() => {
        showToast('Teks disalin!');
        btn.textContent = '✓ Disalin!';
        setTimeout(() => btn.textContent = btn.dataset.originalText || 'Salin', 2000);
      });
    });
  });

  // Export Helper (CSV/Excel links)
  document.querySelectorAll('[data-export]').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const url = link.getAttribute('href');
      const type = link.dataset.export;
      const linkEl = document.createElement('a');
      linkEl.href = url;
      linkEl.download = `kesmas-${type}-${new Date().toISOString().slice(0,10)}.csv`;
      document.body.appendChild(linkEl);
      linkEl.click();
      document.body.removeChild(linkEl);
      showToast(`Export ${type.toUpperCase()} dimulai...`);
    });
  });

  // Global Toast (footer helper)
  window.showToast = function(msg) {
    const toastEl = document.getElementById('miniToast');
    if (toastEl) {
      const toastBody = toastEl.querySelector('.toast-body');
      toastBody.textContent = msg;
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    }
  };

})();

