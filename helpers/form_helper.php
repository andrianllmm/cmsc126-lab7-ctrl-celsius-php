<?php
/**
 * Renders a labeled <input> with standard styling.
 *
 * @param string $name       Field name / id
 * @param string $label      Human-readable label text
 * @param array  $attrs      Extra HTML attributes (type, value, placeholder, etc.)
 */
function formField(string $name, string $label, array $attrs = []): string
{
    $inputClass = 'w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800
                   placeholder-gray-400 focus:outline-none focus:border-red-800
                   focus:ring-2 focus:ring-red-800/10 transition';

    $value = htmlspecialchars($attrs['value'] ?? '');
    unset($attrs['value']);

    $attrStr = '';
    foreach ($attrs as $k => $v) {
        $attrStr .= $v === true ? " $k" : " $k=\"" . htmlspecialchars((string)$v) . '"';
    }

    return <<<HTML
        <label for="$name" class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-1.5">
            $label
        </label>
        <input id="$name" name="$name" value="$value" required class="$inputClass"$attrStr>
    HTML;
}

/**
 * Renders a labeled section divider (title + horizontal rule).
 */
function sectionDivider(string $title): string
{
    return <<<HTML
        <div class="flex items-center gap-3 mb-4">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-widest whitespace-nowrap">$title</p>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>
    HTML;
}

/**
 * Returns 'selected' if the student's year_level matches $year.
 */
function yearSelected(?array $student, int $year): string
{
    return isset($student['year_level']) && (int)$student['year_level'] === $year
        ? 'selected'
        : '';
}
