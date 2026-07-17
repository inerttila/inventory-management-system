@props(['disabled' => false])

<div x-data="{
    model: @entangle($attributes->wire('model')),
    display: '',
    timeout: null,
    init() {
        this.display = this.format(this.model);
        this.$watch('model', value => {
            const current = this.parse(this.display);
            const next = parseFloat(value);
            if (isNaN(current) || isNaN(next) || Math.abs(current - next) > 0.0000001) {
                this.display = this.format(value);
            }
        });
    },
    parse(value) {
        if (value === null || value === undefined || value === '') return NaN;
        let raw = String(value);
        if (window.thousandSeparator) {
            raw = raw.split(window.thousandSeparator).join('');
        }
        if (window.decimalSeparator && window.decimalSeparator !== '.') {
            raw = raw.replace(window.decimalSeparator, '.');
        }
        raw = raw.replace(/[^0-9.-]/g, '');
        if (raw === '' || raw === '-' || raw === '.' || raw === '-.') return NaN;
        return parseFloat(raw);
    },
    format(value) {
        if (value === null || value === undefined || value === '') return '';

        // Preserve in-progress decimal typing (e.g. '10.')
        if (typeof value === 'string' && /[.,]$/.test(value)) {
            return value.replace('.', window.decimalSeparator || '.');
        }

        let amount = typeof value === 'number' ? value : this.parse(value);
        if (isNaN(amount)) return '';

        let isNegative = amount < 0;
        amount = Math.abs(amount);

        const fractions = Number.isFinite(window.currencyFraction) ? window.currencyFraction : 0;
        const strAmount = amount.toFixed(fractions);
        const parts = strAmount.split('.');
        let integerPart = parts[0];
        const decimalPart = parts.length > 1 && fractions > 0
            ? (window.decimalSeparator || '.') + parts[1]
            : '';

        const rgx = /(\d+)(\d{3})/;
        while (rgx.test(integerPart)) {
            integerPart = integerPart.replace(rgx, '$1' + (window.thousandSeparator || ',') + '$2');
        }

        const num = integerPart + decimalPart;
        return isNegative ? '-' + num : num;
    },
    update(event) {
        let raw = String(event.target.value ?? '');
        if (window.thousandSeparator) {
            raw = raw.split(window.thousandSeparator).join('');
        }
        if (window.decimalSeparator && window.decimalSeparator !== '.') {
            raw = raw.replace(window.decimalSeparator, '.');
        }
        raw = raw.replace(/[^0-9.-]/g, '');

        // Keep a single leading minus and a single decimal point
        raw = raw.replace(/(?!^)-/g, '');
        const firstDot = raw.indexOf('.');
        if (firstDot !== -1) {
            raw = raw.slice(0, firstDot + 1) + raw.slice(firstDot + 1).replace(/\./g, '');
        }

        this.display = raw === ''
            ? ''
            : raw.replace('.', window.decimalSeparator || '.');

        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => {
            if (raw === '' || raw === '-' || raw === '.' || raw === '-.' || raw.endsWith('.')) {
                return;
            }
            this.model = parseFloat(raw) || 0;
            this.display = this.format(this.model);
        }, 500);
    }
}"
class="w-full"
>
    <input
        {{ $disabled ? 'disabled' : '' }}
        {!! $attributes->whereDoesntStartWith('wire:model')->merge(['class' => 'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50']) !!}
        type="text"
        inputmode="decimal"
        :value="display"
        @input="update($event)"
        @blur="display = format(model)"
    />
</div>
