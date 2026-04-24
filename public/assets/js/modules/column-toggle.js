/**
 * Initializes column visibility toggle behavior for a table.
 */
export function initColumnToggle() {
  const btn = document.getElementById("colToggleBtn");
  const dropdown = document.getElementById("colDropdown");

  const STORAGE_KEY = "student_table_columns";

  /**
   * Retrieve saved column visibility state from localStorage.
   */
  function getSavedState() {
    const saved = localStorage.getItem(STORAGE_KEY);
    return saved ? JSON.parse(saved) : null;
  }

  /**
   * Save column visibility state to localStorage.
   */
  function saveState(state) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
  }

  /**
   * Apply visibility state to table columns and sync checkbox UI.
   */
  function applyVisibility(state) {
    Object.keys(state).forEach((col) => {
      // Show/hide all elements belonging to this column index
      document.querySelectorAll(`[data-col="${col}"]`).forEach((el) => {
        el.style.display = state[col] ? "" : "none";
      });

      // Sync checkbox state with saved preference
      const checkbox = document.querySelector(`input[data-col="${col}"]`);
      if (checkbox) checkbox.checked = state[col];
    });
  }

  /**
   * Default column visibility state (all visible)
   * Used when no saved state exists in localStorage
   */
  let state = getSavedState() || {
    0: true,
    1: true,
    2: true,
    3: true,
    4: true,
    5: true,
    6: true,
    7: true,
  };

  // Apply initial visibility on page load
  applyVisibility(state);

  /**
   * Toggle dropdown visibility when button is clicked
   */
  btn.addEventListener("click", () => {
    dropdown.classList.toggle("hidden");
  });

  /**
   * Close dropdown when clicking outside of it
   */
  document.addEventListener("click", (e) => {
    if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
      dropdown.classList.add("hidden");
    }
  });

  /**
   * Handle checkbox changes
   */
  document
    .querySelectorAll('input[type="checkbox"][data-col]')
    .forEach((cb) => {
      cb.addEventListener("change", function () {
        const col = this.dataset.col;

        // temporarily apply change
        state[col] = this.checked;

        // count how many are still visible
        const visibleCount = Object.values(state).filter(Boolean).length;

        // prevent 0 selections
        if (visibleCount === 0) {
          state[col] = true; // revert last action
          this.checked = true; // force checkbox back on
          return;
        }

        applyVisibility(state);
        saveState(state);
      });
    });
}
