@extends('layouts.app')

@section('title', 'Layanan — GASS')

@section('body_class', 'services-page-body')

@push('styles')
<style>
	.services-page-body .services-page { padding: 34px 24px 28px; border: 1px solid rgba(103, 232, 249, .24); border-radius: 14px; background: rgba(5, 11, 24, .72); box-shadow: 0 18px 45px rgba(2, 11, 31, .2), inset 0 1px 0 rgba(255,255,255,.08); }
	.services-page-body .process-light { margin-top: 55px; }
	.services-page-body .page-hero { padding-bottom: 54px; }
	.package-intro { max-width: 1240px; margin: 0 auto 28px; padding: 0 32px; text-align: center; }
	.package-intro h2 { margin: 0 0 8px; color: #f7f9fc; font-size: clamp(30px, 4vw, 48px); line-height: .95; letter-spacing: -.06em; }
	.package-intro p { margin: 0; color: #b9c9dc; font-size: 13px; }
	.package-intro:after { content: ""; display: block; width: 70px; height: 3px; margin: 13px auto 0; background: #087bdc; }
	.package-grid { display: grid; grid-template-columns: 1fr; gap: 28px; }
	.package-panel { min-width: 0; padding: 20px; border: 1px solid rgba(6, 27, 69, .14); border-radius: 7px; background: rgba(255, 255, 255, .88); color: #061b45; box-shadow: 0 8px 24px rgba(6, 27, 69, .08); }
	.package-panel.video { --package-color: #087bdc; --package-soft: #e8f3ff; }
	.package-panel.photo { --package-color: #139447; --package-soft: #e9f8ef; }
	.package-heading { display: flex; align-items: center; gap: 10px; padding-bottom: 16px; border-bottom: 1px solid rgba(6, 27, 69, .11); color: var(--package-color); font-size: 15px; font-weight: 700; text-transform: uppercase; }
	.package-heading strong { margin-left: auto; font: 11px var(--mono); text-transform: none; }
	.package-icon { display: grid; width: 28px; height: 28px; place-items: center; border-radius: 5px; background: var(--package-soft); font-size: 16px; }
	.package-tabs { display: flex; gap: 22px; margin: 0 -20px 18px; padding: 0 20px; border-bottom: 1px solid rgba(6, 27, 69, .11); }
	.package-tab { padding: 11px 0 9px; border: 0; border-bottom: 2px solid transparent; background: transparent; color: #61708d; font: 11px var(--display); font-weight: 600; white-space: nowrap; }
	.package-tab.active { border-bottom-color: var(--package-color); color: var(--package-color); }
	.package-tab-note { align-self: center; color: #9ca3af; font-size: 11px; }
	.package-content { display: grid; grid-template-columns: 1fr 2.25fr; gap: 18px; }
	.package-features h3 { margin: 2px 0 14px; font-size: 12px; text-transform: uppercase; }
	.package-features ul { display: grid; gap: 12px; margin: 0; padding: 0; list-style: none; }
	.package-features h3 { color: #061b45; }
	.package-features li { display: flex; gap: 8px; color: #1f3557; font-size: 11px; line-height: 1.25; }
	.package-features li:before { content: "✓"; color: var(--package-color); font-weight: 700; }
	.package-features small { display: block; margin-top: 3px; color: #334d73; font-size: 9px; }
	.package-options { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 9px; }
	.video .package-options { grid-template-columns: repeat(4, minmax(0, 1fr)); }
	.package-option { display: flex; min-width: 0; flex-direction: column; padding: 14px 10px 10px; border: 1px solid rgba(6, 27, 69, .12); border-radius: 6px; background: #fff; }
	.package-option.featured { border: 2px solid var(--package-color); padding: 13px 9px 9px; }
	.package-badge { align-self: center; min-height: 15px; margin: -22px 0 9px; padding: 3px 7px; border-radius: 10px; background: var(--package-color); color: #fff; font: 8px var(--mono); text-transform: uppercase; }
	.package-option h4 { margin: 0 0 8px; color: var(--package-color); font-size: 11px; text-align: center; text-transform: uppercase; }
	.package-price { margin: 0; color: #061b45; font-size: 19px; font-weight: 700; text-align: center; white-space: nowrap; }
	.package-price .price-original, .monthly-service-price .price-original { display: block; margin-bottom: 3px; color: #8793a5; font-size: 10px; font-weight: 400; text-decoration: line-through; }
	.package-price .price-final, .monthly-service-price .price-final { display: block; }
	.package-price .price-discount, .monthly-service-price .price-discount { display: inline-block; margin-top: 5px; padding: 3px 6px; border-radius: 3px; background: #e15b4f; color: #fff; font: 8px var(--mono); text-transform: uppercase; }
	.package-unit { margin: 2px 0 13px; color: #61708d; font-size: 9px; text-align: center; }
	.package-option ul { display: grid; gap: 7px; margin: 0 0 14px; padding: 0; list-style: none; }
	.package-option li { color: #52617b; font-size: 9px; line-height: 1.25; }
	.package-option li:before { content: "✓"; margin-right: 4px; color: var(--package-color); }
	.package-cta { margin-top: auto; padding: 9px 5px; border: 0; border-radius: 3px; background: var(--package-color); color: #fff; font: 600 10px var(--display); text-align: center; }
	button.package-cta { width: 100%; cursor: pointer; }
	.package-note { display: flex; gap: 9px; margin-top: 18px; padding: 11px; border-radius: 5px; background: var(--package-soft); color: #52617b; font-size: 10px; line-height: 1.35; }
	.package-note strong { display: block; margin-bottom: 2px; color: var(--package-color); }
	.package-note span { font-size: 16px; }
	.package-footer { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 20px; padding: 20px; background: #061b45; color: #fff; }
	.package-footer strong { display: block; margin-bottom: 4px; font-size: 11px; }
	.package-footer span { color: #bbc7dc; font-size: 10px; }
	.integrated-services { margin-top: 70px; }
	.integrated-services-intro { display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; align-items: end; margin-bottom: 28px; }
	.integrated-services-intro h2 { margin: 0; color: #061b45; font-size: clamp(30px, 4vw, 48px); line-height: .95; letter-spacing: -.06em; }
	.integrated-services-intro p { max-width: 520px; margin: 0; color: #61708d; font-size: 13px; line-height: 1.6; }
	.integrated-services-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 10px; }
	.integrated-service { min-width: 0; padding: 18px 14px; border-top: 3px solid var(--service-color); background: #fff; box-shadow: 0 8px 24px rgba(6, 27, 69, .07); }
	.integrated-service:nth-child(1) { --service-color: #087bdc; }
	.integrated-service:nth-child(2) { --service-color: #e15b4f; }
	.integrated-service:nth-child(3) { --service-color: #139447; }
	.integrated-service:nth-child(4) { --service-color: #8b5cf6; }
	.integrated-service:nth-child(5) { --service-color: #e39a18; }
	.integrated-service-number { display: block; margin-bottom: 18px; color: var(--service-color); font: 11px var(--mono); }
	.integrated-service h3 { margin: 0 0 12px; color: #061b45; font-size: 13px; line-height: 1.15; }
	.integrated-service ul { display: grid; gap: 8px; margin: 0; padding: 0; list-style: none; }
	.integrated-service li { color: #52617b; font-size: 10px; line-height: 1.3; }
	.integrated-service li::before { content: "✓"; margin-right: 5px; color: var(--service-color); font-weight: 700; }
	.service-flow { display: flex; align-items: center; gap: 8px; margin-top: 18px; padding: 16px 18px; overflow-x: auto; background: #061b45; color: #fff; }
	.service-flow strong { flex: 1 0 auto; font-size: 11px; letter-spacing: .03em; }
	.service-flow span { color: #7fc4ff; font: 10px var(--mono); }
	.service-model { margin: 12px 0 0; padding: 16px 18px; border-left: 3px solid #f7c934; background: #fff; color: #52617b; font-size: 12px; line-height: 1.5; }
	.service-model strong { color: #061b45; }
	.monthly-services { margin-top: 70px; }
	.monthly-services-intro { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 26px; }
	.monthly-services-intro h2 { margin: 0; color: #061b45; font-size: clamp(30px, 4vw, 48px); line-height: .95; letter-spacing: -.06em; }
	.monthly-services-intro p { max-width: 460px; margin: 0; color: #61708d; font-size: 13px; line-height: 1.5; }
	.monthly-services-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; }
	.monthly-service { display: flex; min-width: 0; flex-direction: column; padding: 18px 15px; border-top: 3px solid #087bdc; background: #fff; box-shadow: 0 8px 24px rgba(6, 27, 69, .08); }
	.monthly-service:nth-child(2) { border-top-color: #139447; }
	.monthly-service:nth-child(3) { border-top-color: #e39a18; }
	.monthly-service:nth-child(4) { border-top-color: #8b5cf6; }
	.monthly-service h3 { margin: 0 0 8px; color: #061b45; font-size: 15px; text-transform: uppercase; }
	.monthly-service-price { margin: 0 0 15px; color: #061b45; font-size: 22px; font-weight: 700; }
	.monthly-service-meta { display: grid; gap: 5px; margin: 0 0 15px; color: #61708d; font-size: 11px; }
	.monthly-service ul { display: grid; gap: 7px; margin: 0 0 18px; padding: 0; list-style: none; }
	.monthly-service li { color: #52617b; font-size: 10px; line-height: 1.3; }
	.monthly-service li::before { content: "✓"; margin-right: 5px; color: #087bdc; font-weight: 700; }
	.monthly-service .package-cta { margin-top: auto; }
	.monthly-service button.package-cta { display: block !important; position: relative; z-index: 2; visibility: visible !important; opacity: 1 !important; min-height: 42px; margin-top: 18px; background: #087bdc !important; color: #fff !important; }
	.monthly-service button.package-cta:hover, .monthly-service button.package-cta:focus-visible { background: #061b45 !important; color: #fff !important; outline: 2px solid #67e8f9; outline-offset: 2px; }
	.positioning-strip { margin-top: 14px; padding: 15px 18px; background: #061b45; color: #fff; font-size: 12px; line-height: 1.5; }
	.positioning-strip strong { color: #67e8f9; }
	.order-modal { display: grid; position: fixed; inset: 0; z-index: 2000 !important; isolation: isolate; place-items: center; padding: 20px; background: rgba(6, 27, 69, .72); opacity: 0; pointer-events: none; transition: opacity .2s ease; }
	.services-page-body.order-modal-open main { z-index: 2001; }
	.order-modal.is-open { opacity: 1; pointer-events: auto; }
	.order-dialog { position: relative; width: min(100%, 560px); max-height: min(720px, 92vh); overflow: auto; padding: 32px; background: var(--paper); color: var(--ink); box-shadow: 12px 12px 0 rgba(0, 0, 0, .22); transform: translateY(12px); transition: transform .2s ease; }
	.order-modal.is-open .order-dialog { transform: translateY(0); }
	.order-close { position: absolute; top: 15px; right: 18px; border: 0; background: none; color: var(--ink); font-size: 28px; line-height: 1; cursor: pointer; }
	.order-progress { display: flex; align-items: center; margin-bottom: 28px; color: #9aa5b9; font: 11px var(--mono); }
	.order-progress span { display: grid; width: 27px; height: 27px; place-items: center; border: 1px solid currentColor; border-radius: 50%; }
	.order-progress span.is-active { background: var(--ink); color: var(--paper); }
	.order-progress i { flex: 1; height: 1px; margin: 0 8px; background: #c5cad3; }
	.order-dialog h2 { max-width: 380px; margin: 0 0 22px; font-size: clamp(34px, 7vw, 54px); line-height: .9; letter-spacing: -.06em; }
	.order-lead { margin: -10px 0 22px; color: #61708d; font-size: 13px; }
	.order-step { display: none; }
	.order-step.is-active { display: block; }
	.order-summary, .invoice-box, .payment-box { display: grid; gap: 7px; margin-bottom: 20px; padding: 16px; border: 1px solid var(--line); background: rgba(255, 255, 255, .5); }
	.order-summary strong { font-size: 17px; }
	.order-summary span, .invoice-box span, .payment-box span { color: #61708d; font-size: 11px; }
	.order-dialog label, .order-dialog legend { display: grid; gap: 7px; margin-bottom: 18px; font-size: 12px; font-weight: 600; }
	.order-dialog input:not([type="checkbox"]), .order-dialog textarea, .order-dialog select { width: 100%; padding: 11px; border: 1px solid var(--line); border-radius: 3px; background: #fff; color: var(--ink); font: 13px var(--display); }
	.order-dialog textarea { resize: vertical; }
	.order-dialog fieldset { margin: 0 0 18px; padding: 0; border: 0; }
	.order-check { display: flex !important; grid-template-columns: auto 1fr; align-items: center; gap: 9px !important; margin-bottom: 10px !important; font-weight: 400 !important; }
	.order-check input { accent-color: #087bdc; }
	.order-next, .order-confirm { display: flex; width: 100%; align-items: center; justify-content: space-between; padding: 13px 15px; border: 1px solid var(--ink); background: var(--ink); color: var(--paper); font: 600 12px var(--display); cursor: pointer; }
	.order-back { margin-top: 12px; padding: 0; border: 0; background: none; color: #61708d; font: 11px var(--display); cursor: pointer; }
	.invoice-box div { display: flex; justify-content: space-between; gap: 15px; padding-bottom: 9px; border-bottom: 1px solid rgba(22, 22, 22, .1); }
	.invoice-box div:last-child { border-bottom: 0; }
	.invoice-box strong { font-size: 12px; text-align: right; }
	.invoice-total { margin-top: 5px; padding-top: 14px; border-top: 2px solid var(--ink); }
	.invoice-total strong { font-size: 20px; }
	.cart-card { display: grid; gap: 9px; margin-bottom: 20px; padding: 17px; border: 1px solid #087bdc; background: #e8f3ff; }
	.cart-card .eyebrow { margin: 0; color: #087bdc; font-size: 10px; }
	.cart-card strong { font-size: 18px; }
	.cart-card div { display: flex; justify-content: space-between; gap: 12px; color: #61708d; font-size: 12px; }
	.cart-card [data-cart-total] { color: #061b45; font-weight: 700; }
	.cart-items { display: grid; gap: 10px; margin-bottom: 16px; }
	.cart-item { display: grid; grid-template-columns: 1fr auto; gap: 5px 12px; padding: 12px; border: 1px solid rgba(6, 27, 69, .12); background: #fff; }
	.cart-item strong { font-size: 13px; }
	.cart-item span { color: #61708d; font-size: 11px; }
	.cart-item-price { text-align: right; }
	.cart-quantity, .request-quantity { display: flex; align-items: center; gap: 9px; color: #061b45; font-size: 12px; }
	.cart-quantity { grid-column: 1 / -1; justify-content: flex-end; }
	.cart-quantity button, .request-quantity button { display: grid; width: 25px; height: 25px; place-items: center; padding: 0; border: 1px solid #087bdc; background: #fff; color: #087bdc; font: 600 15px var(--display); cursor: pointer; }
	.cart-quantity strong, .request-quantity strong { min-width: 18px; text-align: center; }
	.cart-quantity span { margin-left: auto; color: #061b45; font-weight: 700; }
	.cart-item-actions { display: flex; grid-column: 1 / -1; gap: 7px; }
	.cart-item button { padding: 7px 9px; border: 1px solid #c5cad3; background: transparent; color: #61708d; font: 10px var(--display); cursor: pointer; }
	.cart-item button[data-cart-edit] { border-color: #087bdc; color: #087bdc; }
	.cart-add { width: 100%; margin: 0 0 12px; padding: 11px; border: 1px dashed #087bdc; background: transparent; color: #087bdc; font: 600 11px var(--display); cursor: pointer; }
	.payment-box { border-color: #087bdc; background: #e8f3ff; }
	.payment-box strong { color: #087bdc; font-size: 13px; }
	.payment-box b { font: 700 20px var(--mono); letter-spacing: .03em; }
	.order-success { text-align: center; }
	.order-success > span { display: grid; width: 48px; height: 48px; margin: 0 auto 16px; place-items: center; border-radius: 50%; background: #139447; color: #fff; font-size: 25px; }
	.order-success h3 { margin: 0 0 10px; font-size: 24px; }
	.order-success p { margin: 0 0 22px; color: #61708d; font-size: 13px; line-height: 1.5; }
	.order-success .order-confirm { justify-content: center; gap: 14px; background: #139447; border-color: #139447; }
	.request-checkout { display: grid; position: fixed; inset: 0; z-index: 3000; place-items: center; padding: 18px; background: rgba(6, 27, 69, .78); }
	.request-checkout[hidden] { display: none; }
	.request-dialog { width: min(100%, 680px); max-height: 94vh; overflow: auto; padding: 30px; background: var(--paper); color: var(--ink); box-shadow: 12px 12px 0 rgba(0, 0, 0, .2); }
	.request-head { display: flex; justify-content: space-between; gap: 20px; margin-bottom: 22px; }
	.request-head h2 { margin: 0; font-size: clamp(32px, 6vw, 52px); line-height: .9; letter-spacing: -.06em; }
	.request-close { width: 42px; height: 42px; border: 1px solid var(--ink); background: transparent; font-size: 24px; cursor: pointer; }
	.request-steps { display: flex; gap: 5px; margin-bottom: 24px; overflow: auto; }
	.request-steps span { flex: 1; min-width: 70px; padding: 8px 5px; border-bottom: 2px solid #c5cad3; color: #61708d; font: 10px var(--mono); text-align: center; }
	.request-steps span.is-active { border-color: #087bdc; color: #087bdc; }
	.request-panel { display: grid; gap: 15px; }
	.request-panel[hidden] { display: none; }
	.request-panel h3 { margin: 0; font-size: 22px; }
	.request-panel > p { margin: 0; color: #61708d; font-size: 13px; line-height: 1.45; }
	.request-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 13px; }
	.request-field { display: grid; gap: 6px; color: var(--ink); font-size: 11px; font-weight: 600; }
	.request-field.full { grid-column: 1 / -1; }
	.request-field input, .request-field textarea, .request-field select { width: 100%; padding: 12px; border: 1px solid #b9c2d1; border-radius: 3px; background: #fff; color: var(--ink); font: 13px var(--display); }
	.request-field input, .request-field select { min-height: 45px; }
	.request-field textarea { resize: vertical; }
	.request-field input:focus, .request-field textarea:focus, .request-field select:focus { outline: 2px solid #087bdc; outline-offset: 1px; }
	.request-cart { display: grid; gap: 9px; }
	.request-cart-item { display: grid; grid-template-columns: 1fr auto auto; align-items: center; gap: 10px; padding: 13px; border: 1px solid #c5cad3; background: #fff; }
	.request-cart-item strong { font-size: 13px; }
	.request-cart-item span { color: #61708d; font-size: 11px; }
	.request-quantity { justify-self: end; }
	.request-cart-item button { padding: 7px; border: 0; background: transparent; color: #c84b3d; font: 11px var(--display); cursor: pointer; }
	.request-quantity button { padding: 0; border: 1px solid #087bdc; color: #087bdc; font-size: 15px; }
	.request-total { display: flex; justify-content: space-between; padding-top: 15px; border-top: 2px solid var(--ink); font-size: 15px; font-weight: 700; }
	.request-actions { display: flex; flex-wrap: wrap; gap: 10px; justify-content: flex-end; margin-top: 6px; }
	.request-actions button, .request-actions a { padding: 12px 15px; border: 1px solid var(--ink); background: var(--ink); color: var(--paper); font: 600 11px var(--display); cursor: pointer; text-decoration: none; }
	.request-actions .request-secondary { background: transparent; color: var(--ink); }
	.request-review, .request-invoice { display: grid; gap: 11px; padding: 17px; border: 1px solid #c5cad3; background: #fff; }
	.request-review > div { display: flex; justify-content: space-between; gap: 15px; padding-bottom: 9px; border-bottom: 1px solid #e1e4e9; font-size: 12px; }
	.request-review > div:last-child { border-bottom: 0; }
	.request-review span, .request-invoice .request-total span { color: #61708d; }
	.request-invoice .request-total { padding-top: 13px; border-top: 2px solid var(--ink); font-size: 18px; }
	.invoice-preview { display: grid; gap: 15px; padding: 17px; border: 1px solid #d8dee8; background: #fff; }
	.invoice-brand { display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 10px; padding-bottom: 14px; border-bottom: 3px solid #087bdc; }
	.invoice-brand > strong { color: #087bdc; font-size: 25px; }
	.invoice-brand > span { color: #61708d; font: 8px/1.2 var(--mono); }
	.invoice-brand > b { font-size: 15px; text-align: right; }
	.invoice-meta { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; color: #61708d; font-size: 9px; }
	.invoice-meta strong { display: block; margin-top: 4px; color: var(--ink); font-size: 10px; }
	.invoice-table { width: 100%; overflow: hidden; }
	.invoice-table-head, .invoice-line { display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) minmax(0, 1fr); gap: 10px; padding: 11px 10px; border-bottom: 1px solid #cbd4e1; color: #061b45; font-size: 11px; }
	.invoice-table-head { background: #087bdc; color: #fff; font-weight: 700; }
	.invoice-line { background: #fff; font-weight: 500; }
	.invoice-line:nth-child(even) { background: #f4f8fc; }
	.invoice-line small { display: block; margin-top: 4px; color: #52617b; font-size: 9px; line-height: 1.3; }
	.invoice-line > span:first-child { min-width: 0; overflow-wrap: anywhere; }
	.invoice-line > span:nth-child(2), .invoice-line strong { color: #061b45; font-weight: 700; text-align: right; }
	.invoice-table-head > span:nth-child(2), .invoice-table-head > span:nth-child(3) { text-align: right; }
	.request-invoice .invoice-preview .invoice-total strong, .request-invoice .invoice-preview .invoice-bottom .invoice-total strong { color: #061b45 !important; font-size: 20px; font-weight: 800; }
	.request-invoice .invoice-preview .invoice-summary { display: grid; min-width: 220px; gap: 7px; margin-left: auto; padding-top: 12px; border-top: 2px solid #061b45; }
	.request-invoice .invoice-preview .invoice-summary div { display: flex; justify-content: space-between; gap: 20px; color: #61708d; font-size: 10px; }
	.request-invoice .invoice-preview .invoice-summary strong { color: #061b45; text-align: right; }
	.invoice-bottom { display: flex; justify-content: space-between; gap: 15px; padding-top: 8px; }
	.invoice-bottom p { max-width: 220px; margin: 5px 0 0; color: #61708d; font-size: 9px; line-height: 1.35; }
	.invoice-total { display: grid; align-content: end; gap: 5px; text-align: right; }
	.invoice-total span { color: #61708d; font-size: 9px; }
	.invoice-total strong { color: #061b45; font-size: 20px; font-weight: 800; }
	.invoice-status { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding-top: 11px; border-top: 1px solid #d8dee8; font-size: 10px; }
	.invoice-status { display: none; }
	.request-status { display: inline-flex; padding: 6px 9px; background: #fff0bf; color: #765800; font: 10px var(--mono); }
	.request-payment { padding: 17px; border: 1px solid #087bdc; background: #e8f3ff; }
	.request-payment strong, .request-payment b, .request-payment span { display: block; margin-bottom: 6px; }
	.request-cart-trigger { position: fixed; right: 22px; bottom: 22px; z-index: 1200; padding: 14px 18px; border: 1px solid #22d3ee; background: linear-gradient(135deg, #0f2742, #087bdc); color: #fff; box-shadow: 0 0 28px rgba(34, 211, 238, .28); font: 500 14px var(--display); cursor: pointer; }
	.request-cart-trigger span { display: inline-grid; min-width: 22px; height: 22px; margin-left: 7px; place-items: center; border-radius: 50%; background: #67e8f9; color: #050816; font: 500 12px var(--display); }
	.request-drawer { position: fixed; top: 0; right: 0; bottom: 0; z-index: 2500; width: min(390px, 92vw); overflow: auto; padding: 30px 22px; background: var(--paper); color: var(--ink); box-shadow: -10px 0 30px rgba(0, 0, 0, .18); transform: translateX(105%); transition: transform .25s ease; }
	.request-drawer.is-open { transform: translateX(0); }
	.request-drawer-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
	.request-drawer-head h2 { margin: 0; font-size: 30px; letter-spacing: -.05em; }
	.request-drawer-close { width: 40px; height: 40px; border: 1px solid var(--ink); background: transparent; font-size: 22px; cursor: pointer; }
	.request-drawer .request-cart-item { grid-template-columns: 1fr; gap: 6px; }
	.request-drawer .request-quantity { justify-self: start; }
	.request-item-custom { display: grid; gap: 9px; margin-top: 6px; padding-top: 10px; border-top: 1px solid #e1e4e9; }
	.request-item-custom .request-field { font-size: 10px; }
	.request-item-custom input, .request-item-custom textarea { padding: 9px; font-size: 12px; }
	.request-drawer .request-cart-item button { justify-self: start; margin-right: 6px; border: 1px solid #087bdc; color: #087bdc; }
	.request-drawer-backdrop { position: fixed; inset: 0; z-index: 2499; background: rgba(6, 27, 69, .45); }
	.request-drawer-backdrop[hidden] { display: none; }
	@media (max-width: 600px) { .request-checkout { align-items: end; padding: 8px; } .request-dialog { padding: 22px 16px 18px; border-radius: 9px 9px 3px 3px; } .request-head { margin-bottom: 18px; } .request-grid { grid-template-columns: 1fr; gap: 11px; } .request-field.full { grid-column: auto; } .request-cart-item { grid-template-columns: 1fr auto; } .request-cart-item button { grid-column: 2; grid-row: 1 / span 2; } .request-actions { display: grid; grid-template-columns: 1fr; } .request-actions button, .request-actions a { width: 100%; text-align: center; } }
	@media (max-width: 600px) { .request-cart-trigger { right: 14px; bottom: 14px; } .request-drawer { width: min(100%, 380px); padding: 24px 18px; } }
	.order-dialog button, .order-dialog input, .order-dialog textarea, .order-dialog select { -webkit-tap-highlight-color: transparent; }
	.order-dialog input:focus, .order-dialog textarea:focus, .order-dialog select:focus { outline: 2px solid #087bdc; outline-offset: 1px; }
	@media (max-width: 600px) {
		.order-modal { align-items: end; padding: 10px; }
		.order-dialog { width: 100%; max-height: 94vh; padding: 25px 18px 20px; border-radius: 10px 10px 4px 4px; box-shadow: 0 8px 30px rgba(0, 0, 0, .25); }
		.order-close { top: 13px; right: 14px; width: 42px; height: 42px; }
		.order-progress { margin: 0 26px 24px 0; }
		.order-progress span { width: 25px; height: 25px; font-size: 10px; }
		.order-progress i { margin: 0 5px; }
		.order-dialog h2 { margin-bottom: 20px; font-size: 39px; }
		.order-lead { font-size: 12px; line-height: 1.45; }
		.order-summary, .invoice-box, .payment-box { padding: 14px; }
		.order-summary strong { font-size: 16px; }
		.order-dialog label, .order-dialog legend { margin-bottom: 16px; }
		.order-dialog input:not([type="checkbox"]), .order-dialog textarea, .order-dialog select { min-height: 48px; padding: 12px; font-size: 16px; }
		.order-dialog textarea { min-height: 96px; }
		.invoice-table-head, .invoice-line { grid-template-columns: minmax(0, 1.6fr) minmax(0, .9fr) minmax(0, .9fr); gap: 5px; padding: 10px 6px; font-size: 9px; }
		.invoice-line small { font-size: 8px; }
		.order-check { min-height: 38px; }
		.order-next, .order-confirm { min-height: 48px; padding: 13px 14px; }
		.invoice-box div { align-items: flex-start; flex-wrap: wrap; }
		.invoice-box strong { max-width: 68%; line-height: 1.35; }
		.invoice-total strong { font-size: 18px; }
		.payment-box b { font-size: 18px; overflow-wrap: anywhere; }
	}
	@media (max-width: 980px) { .package-content { grid-template-columns: 1fr; } .package-features ul { grid-template-columns: repeat(3, 1fr); } }
	@media (max-width: 980px) { .integrated-services-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
	@media (max-width: 980px) { .monthly-services-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
	@media (max-width: 800px) { .services-page-body .page-hero { padding-bottom: 38px; } .services-page-body .services-page { padding: 26px 16px 20px; border-radius: 10px; } .package-intro { padding: 0 4px; } .package-grid { grid-template-columns: 1fr; gap: 18px; } .package-panel { padding: 16px; } .package-tabs { margin-left: -16px; margin-right: -16px; padding-left: 16px; padding-right: 16px; gap: 16px; overflow-x: auto; } .package-options { gap: 6px; } .video .package-options { grid-template-columns: repeat(2, minmax(0, 1fr)); } .package-price { font-size: 14px; } .package-footer { grid-template-columns: repeat(2, 1fr); } }
	@media (max-width: 600px) { .integrated-services { margin-top: 48px; } .integrated-services-intro { grid-template-columns: 1fr; gap: 14px; } .integrated-services-grid { grid-template-columns: 1fr 1fr; } .integrated-service { padding: 15px 12px; } .service-flow { margin-right: -20px; margin-left: -20px; padding-right: 20px; padding-left: 20px; } }
	@media (max-width: 600px) { .monthly-services { margin-top: 48px; } .monthly-services-intro { display: grid; gap: 14px; } }
	@media (max-width: 430px) { .package-content { gap: 22px; } .package-features ul { grid-template-columns: 1fr; gap: 9px; } .package-options { grid-template-columns: 1fr; } .package-option { padding: 13px; } .package-option.featured { padding: 12px; } .package-badge { margin-top: -21px; } .package-footer { padding: 16px; } .integrated-services-grid, .monthly-services-grid { grid-template-columns: 1fr; } .order-dialog { padding: 25px 20px; } }
	.cart-item button, .cart-add, .request-cart-item button, .request-actions button, .request-actions a, .request-drawer .request-cart-item button { border-color: #22d3ee; background: rgba(15, 23, 42, .92); color: #fff; font-family: "Roboto", sans-serif; font-size: 14px; font-weight: 500; }
	.cart-item button:hover, .cart-add:hover, .request-cart-item button:hover, .request-actions button:hover, .request-actions a:hover { border-color: #67e8f9; background: #087bdc; color: #fff; }
	.cart-quantity button, .request-quantity button { border-color: #22d3ee; background: #0f2742; color: #67e8f9; }
	.cart-quantity button:hover, .request-quantity button:hover { background: #22d3ee; color: #050816; }
	.request-actions .request-secondary { border-color: #67e8f9; background: transparent; color: #67e8f9; }
	.invoice-table-head { color: #fff; }
	.invoice-line, .invoice-line > span:nth-child(2), .invoice-line strong { color: #e5e7eb; }
	.invoice-line { background: #0f172a; }
	.invoice-line:nth-child(even) { background: #16253b; }
	.invoice-line small { color: #9ca3af; }
	.invoice-total strong, .request-total strong { color: #fff; }
	.order-close, .request-close, .request-drawer-close { display: grid; place-items: center; border: 1px solid #22d3ee; background: #22d3ee; color: #050816; font-family: "Roboto", sans-serif; font-weight: 700; }
	.order-close:hover, .request-close:hover, .request-drawer-close:hover { background: #67e8f9; color: #050816; box-shadow: 0 0 22px rgba(103, 232, 249, .55); }
	.order-next, .order-confirm, .request-drawer [data-drawer-continue], .request-actions button:not(.request-secondary), .request-actions a { border-color: #22d3ee; background: linear-gradient(135deg, #087bdc, #1556c0); color: #fff; font-family: "Roboto", sans-serif; font-size: 14px; font-weight: 500; }
	.order-next:hover, .order-confirm:hover, .request-drawer [data-drawer-continue]:hover, .request-actions button:not(.request-secondary):hover, .request-actions a:hover { border-color: #67e8f9; background: linear-gradient(135deg, #119ee9, #256be0); color: #fff; box-shadow: 0 0 26px rgba(34, 211, 238, .38); }
	.order-back, .request-actions .request-secondary { color: #67e8f9; }
</style>
@endpush

@section('content')
<section class="page-hero section-shell"><p class="eyebrow">02 / Layanan GASS</p><h1>Semua yang kamu butuhkan<br>untuk <em>naik level.</em></h1><p class="hero-intro">Dari fondasi digital sampai eksekusi harian, kami bantu brand tampil jelas, konsisten, dan siap bertumbuh.</p></section>
<section class="services section-shell services-page">
	<div class="package-intro"><h2>Pilihan Layanan Kami</h2><p>Solusi konten digital berbasis AI yang kreatif, cepat, dan terjangkau.</p></div>
	<div class="package-grid">
		<article class="package-panel video">
			<div class="package-heading"><span class="package-icon">▣</span><span>A. Video AI</span><strong>01</strong></div>
			<div class="package-tabs"><span class="package-tab active">Layanan &amp; Tarif</span><span class="package-tab-note">Pilih paket berdasarkan kebutuhan dan anggaran</span></div>
			<div class="package-content"><div class="package-features"><h3>Layanan &amp; Tarif</h3><ul><li>AI Video Basic<small>15 - 30 detik</small></li><li>AI Video Standard<small>30 - 60 detik</small></li><li>AI Video Premium<small>60 - 90 detik</small></li><li>AI Video Pro<small>60 - 120 detik</small></li></ul></div><div class="package-options"><div class="package-option"><h4>Basic</h4><p class="package-price">Rp 350.000</p><p class="package-unit">/ Video</p><ul><li>Durasi 15 - 30 detik</li><li>1 konsep konten</li><li>1 - 3 scene</li><li>Visual AI basic</li><li>Produk / objek AI</li><li>Basic animation</li><li>Background music</li><li>Basic sound effect</li><li>Subtitle</li><li>Basic editing</li><li>Format 9:16</li><li>Resolusi Full HD</li><li>Revisi 1x</li></ul><button class="package-cta" type="button" data-order-package="AI Video Basic" data-order-price="350000">Pesan Sekarang</button></div><div class="package-option"><h4>Standard</h4><p class="package-price">Rp 750.000</p><p class="package-unit">/ Video</p><ul><li>Durasi 30 - 60 detik</li><li>Konsep &amp; storyline</li><li>Script/copywriting sederhana</li><li>3 - 6 scene</li><li>Visual AI lebih detail</li><li>AI product visualization</li><li>AI character sederhana</li><li>AI voice over</li><li>Motion/animation</li><li>Professional editing</li><li>Logo &amp; branding klien</li><li>Format 9:16 / 16:9</li><li>Resolusi Full HD</li><li>Revisi 2x</li></ul><button class="package-cta" type="button" data-order-package="AI Video Standard" data-order-price="750000">Pesan Sekarang</button></div><div class="package-option featured"><span class="package-badge">Popular</span><h4>Premium</h4><p class="package-price">Rp 1.500.000</p><p class="package-unit">/ Video</p><ul><li>Durasi 60 - 90 detik</li><li>Creative concept</li><li>Storytelling</li><li>Script profesional</li><li>Storyboard sederhana</li><li>6 - 10 scene</li><li>Premium AI visual</li><li>Custom AI character</li><li>Character consistency</li><li>Product visualization</li><li>Advanced animation</li><li>Cinematic transition</li><li>CTA / Call to Action</li><li>Revisi 2x</li></ul><button class="package-cta" type="button" data-order-package="AI Video Premium" data-order-price="1500000">Pesan Sekarang</button></div><div class="package-option"><span class="package-badge">Best Value</span><h4>Pro</h4><p class="package-price">Rp 2.500.000</p><p class="package-unit">/ Video</p><ul><li>Durasi 60 - 120 detik</li><li>Creative direction</li><li>Konsep campaign</li><li>Professional storytelling</li><li>Professional script</li><li>Storyboard</li><li>8 - 15+ scene</li><li>Cinematic AI visual</li><li>Custom AI character</li><li>Consistent environment</li><li>Product placement</li><li>Advanced AI animation</li><li>Motion graphic</li><li>CTA &amp; campaign message</li><li>Multiple format output</li><li>Revisi 3x</li></ul><button class="package-cta" type="button" data-order-package="AI Video Pro" data-order-price="2500000">Pesan Sekarang</button></div></div></div>
			<div class="package-note"><span>◷</span><div><strong>Butuh video rutin setiap bulan?</strong>Dapatkan harga spesial dengan Paket Langganan Bulanan.</div></div>
		</article>
		<article class="package-panel photo">
			<div class="package-heading"><span class="package-icon">▧</span><span>B. Foto / Carousel</span><strong>02</strong></div>
			<div class="package-tabs"><span class="package-tab active">Layanan &amp; Tarif</span><span class="package-tab-note">Pilih paket berdasarkan kebutuhan dan anggaran</span></div>
			<div class="package-content"><div class="package-features"><h3>Layanan &amp; Tarif</h3><ul><li>Foto AI Product<small>Produk siap tampil</small></li><li>Desain Carousel<small>3 - 10 Slide</small></li><li>Feed Instagram<small>Konten konsisten</small></li><li>Banner Promosi<small>Siap digunakan</small></li><li>Paket Langganan Bulanan<small>Hemat lebih banyak</small></li></ul></div><div class="package-options"><div class="package-option"><h4>Basic</h4><p class="package-price">Rp 60.000</p><p class="package-unit">/ Desain</p><ul><li>1 - 3 Slide</li><li>Desain Profesional</li><li>Foto / Ilustrasi AI</li><li>Revisi 1x</li></ul><button class="package-cta" type="button" data-order-package="Foto AI Basic" data-order-price="60000">Pesan Sekarang</button></div><div class="package-option featured"><span class="package-badge">Popular</span><h4>Standard</h4><p class="package-price">Rp 120.000</p><p class="package-unit">/ Desain</p><ul><li>4 - 6 Slide</li><li>Desain Profesional</li><li>Foto / Ilustrasi AI</li><li>Revisi 2x</li></ul><button class="package-cta" type="button" data-order-package="Foto AI Standard" data-order-price="120000">Pesan Sekarang</button></div><div class="package-option"><span class="package-badge">Best Value</span><h4>Premium</h4><p class="package-price">Rp 200.000</p><p class="package-unit">/ Desain</p><ul><li>7 - 10 Slide</li><li>Desain Premium</li><li>Foto / Ilustrasi AI</li><li>Revisi 3x</li></ul><button class="package-cta" type="button" data-order-package="Foto AI Premium" data-order-price="200000">Pesan Sekarang</button></div></div></div>
			<div class="package-note"><span>◷</span><div><strong>Ingin konten rutin setiap bulan?</strong>Paket Langganan Bulanan siap membantu bisnis Anda lebih konsisten.</div></div>
		</article>
	</div>
	<div class="package-footer"><div><strong>◷ Proses Cepat</strong><span>1 - 3 hari kerja</span></div><div><strong>◷ Revisi Fleksibel</strong><span>Sesuai kebutuhan Anda</span></div><div><strong>◇ Kualitas Terjamin</strong><span>Hasil profesional</span></div><div><strong>✧ Harga Terjangkau</strong><span>Sesuai budget</span></div></div>

<section class="integrated-services section-shell">
	<div class="integrated-services-intro"><div><p class="eyebrow">GASS Digital Solutions</p><h2>Layanan yang saling <em>terhubung.</em></h2></div><p>GASS hadir sebagai Digital Agency &amp; Advertising untuk membantu brand mengelola seluruh kebutuhan digital dalam satu partner yang terintegrasi.</p></div>
	<div class="integrated-services-grid">
		<article class="integrated-service"><span class="integrated-service-number">01</span><h3>Website Management</h3><ul><li>Pembuatan Website</li><li>Update Produk &amp; Layanan</li><li>Update Promo</li><li>Maintenance Website</li><li>Optimasi &amp; Pengelolaan Konten</li></ul></article>
		<article class="integrated-service"><span class="integrated-service-number">02</span><h3>Social Media Management</h3><ul><li>Instagram, TikTok, Facebook</li><li>Content Planning &amp; Publishing</li><li>Pengelolaan 14 Cabang Terintegrasi</li><li>Promo &amp; Campaign</li><li>Social Media Advertising</li><li>Monitoring &amp; Optimasi</li></ul></article>
		<article class="integrated-service"><span class="integrated-service-number">03</span><h3>E-Commerce Management</h3><ul><li>Pembuatan &amp; Pengelolaan Toko Online</li><li>Update Produk &amp; Harga</li><li>Pengelolaan Katalog &amp; Stok</li><li>Promo &amp; Voucher</li><li>Marketplace Advertising</li><li>Monitoring Penjualan &amp; Optimasi</li></ul></article>
		<article class="integrated-service"><span class="integrated-service-number">04</span><h3>AI Video Content</h3><ul><li>Video Promosi AI</li><li>Video Produk</li><li>Video Edukasi</li><li>Video Storytelling</li><li>Video Iklan</li><li>AI Character &amp; Voice Over</li></ul></article>
		<article class="integrated-service"><span class="integrated-service-number">05</span><h3>AI Photo / Carousel Content</h3><ul><li>Foto Produk AI</li><li>Foto Promosi</li><li>Foto Campaign</li><li>Carousel Edukasi</li><li>Carousel Produk</li><li>Konten Branding &amp; Advertising</li></ul></article>
	</div>
	<div class="service-flow"><strong>WEBSITE</strong><span>→</span><strong>MEDIA SOSIAL</strong><span>→</span><strong>E-COMMERCE</strong><span>→</span><strong>KONTEN</strong><span>→</span><strong>ADVERTISING</strong></div>
	<p class="service-model"><strong>Konsep Layanan GASS:</strong> semua layanan dapat dibuat dalam project satuan maupun paket langganan bulanan, sehingga klien dapat menggunakan GASS sebagai partner digital terintegrasi, bukan hanya sebagai penyedia jasa pembuatan konten.</p>
</section>
<section class="monthly-services section-shell">
	<div class="monthly-services-intro"><div><p class="eyebrow">Paket Langganan Bulanan</p><h2>Konten rutin,<br><em>arah tetap tajam.</em></h2></div><p>Untuk kebutuhan produksi konten yang konsisten, GASS menghadirkan paket bulanan dengan kapasitas dan dukungan yang semakin lengkap.</p></div>
	<div class="monthly-services-grid">
		<article class="monthly-service"><h3>Basic</h3><p class="monthly-service-price">Rp 1.200.000</p><div class="monthly-service-meta"><span>4 Video / Bulan</span><span>Durasi 15 - 30 detik</span><span>Konten Rutin</span></div><ul><li>Produksi video AI rutin</li><li>Format 9:16</li><li>Resolusi Full HD</li></ul><button class="package-cta" type="button" data-order-package="AI Video Basic Bulanan" data-order-price="1200000">Pesan Sekarang</button></article>
		<article class="monthly-service"><h3>Standard</h3><p class="monthly-service-price">Rp 2.500.000</p><div class="monthly-service-meta"><span>8 Video / Bulan</span><span>Durasi 30 - 60 detik</span><span>Konten Bisnis</span></div><ul><li>Content planning dasar</li><li>Konsep dan storyline</li><li>Branding dasar</li></ul><button class="package-cta" type="button" data-order-package="AI Video Standard Bulanan" data-order-price="2500000">Pesan Sekarang</button></article>
		<article class="monthly-service"><h3>Premium</h3><p class="monthly-service-price">Rp 4.000.000</p><div class="monthly-service-meta"><span>12 Video / Bulan</span><span>Durasi 30 - 90 detik</span><span>Konten Marketing</span></div><ul><li>Creative concept</li><li>Storytelling dan script</li><li>Premium AI visual</li></ul><button class="package-cta" type="button" data-order-package="AI Video Premium Bulanan" data-order-price="4000000">Pesan Sekarang</button></article>
		<article class="monthly-service"><h3>Pro</h3><p class="monthly-service-price">Rp 6.500.000</p><div class="monthly-service-meta"><span>20 Video / Bulan</span><span>Durasi 30 - 120 detik</span><span>Konten Advertising &amp; Campaign</span></div><ul><li>Content Planning</li><li>Creative Direction</li><li>Branding</li><li>Social Media Support</li></ul><button class="package-cta" type="button" data-order-package="AI Video Pro Bulanan" data-order-price="6500000">Pesan Sekarang</button></article>
	</div>
	<div class="positioning-strip"><strong>POSITIONING GASS:</strong> AI Content Production &nbsp; Concept → Script → AI Visual → Voice Over → Animation → Editing → Branding → Advertising</div>
</section>
</section>
<div class="order-modal" data-order-modal aria-hidden="true"><div class="order-dialog" role="dialog" aria-modal="true" aria-labelledby="order-title"><button class="order-close" type="button" data-order-close aria-label="Tutup pemesanan">×</button><div class="order-progress"><span class="is-active" data-order-progress="1">01</span><i></i><span data-order-progress="2">02</span><i></i><span data-order-progress="3">03</span><i></i><span data-order-progress="4">04</span></div><p class="eyebrow">Pemesanan layanan</p><h2 id="order-title" data-order-title>Ceklis Pesanan</h2><form data-order-form><section class="order-step is-active" data-order-step="1"><p class="order-lead">Sesuaikan kebutuhanmu sebelum lanjut ke invoice.</p><div class="order-summary"><strong data-order-selected>Video AI Standard</strong><span data-order-selected-price>Rp 75.000 / item</span></div><label>Jumlah pesanan<input name="quantity" type="number" min="1" max="99" value="1" required></label><fieldset><legend>Tambahan kebutuhan</legend><label class="order-check"><input name="addon" value="Konsep dan storyboard" type="checkbox"> Konsep dan storyboard</label><label class="order-check"><input name="addon" value="Prioritas pengerjaan" type="checkbox"> Prioritas pengerjaan</label><label class="order-check"><input name="addon" value="Konsultasi konten" type="checkbox"> Konsultasi konten</label></fieldset><label>Brief atau catatan khusus<textarea name="brief" rows="3" placeholder="Ceritakan ide, ukuran, atau referensi konten..." required></textarea></label><button class="order-next" type="button" data-order-next>Lanjut ke Invoice <span>→</span></button></section><section class="order-step" data-order-step="2"><p class="order-lead">Periksa detail pesanan dan total tagihanmu.</p><div class="invoice-box"><div><span>Layanan</span><strong data-invoice-package></strong></div><div><span>Jumlah</span><strong data-invoice-quantity></strong></div><div><span>Tambahan</span><strong data-invoice-addon></strong></div><div class="invoice-total"><span>Total</span><strong data-invoice-total></strong></div></div><button class="order-next" type="button" data-order-next>Lanjut ke Pembayaran <span>→</span></button><button class="order-back" type="button" data-order-back>Kembali</button></section><section class="order-step" data-order-step="3"><p class="order-lead">Lakukan pembayaran ke rekening berikut.</p><div class="payment-box"><strong>Bank BCA</strong><b>1234 5678 90</b><span>a.n. PT GASS Digital Solutions</span></div><label>Metode pembayaran<select name="payment" required><option value="Transfer BCA">Transfer BCA</option><option value="QRIS">QRIS</option></select></label><label class="order-check"><input name="paid" type="checkbox" required> Saya sudah melakukan pembayaran</label><button class="order-next" type="button" data-order-next>Lanjut ke Konfirmasi <span>→</span></button><button class="order-back" type="button" data-order-back>Kembali</button></section><section class="order-step" data-order-step="4"><p class="order-lead">Lengkapi data agar tim kami bisa mengonfirmasi pesanan.</p><label>Nama lengkap<input name="name" type="text" autocomplete="name" required></label><label>Email atau WhatsApp<input name="contact" type="text" autocomplete="email" required></label><button class="order-confirm" type="submit">Konfirmasi Pesanan <span>↗</span></button><button class="order-back" type="button" data-order-back>Kembali</button></section><section class="order-success" data-order-success hidden><span>✓</span><h3>Pesanan siap dikonfirmasi</h3><p>Detail pesanan sudah dirangkum. Kirim konfirmasi ke WhatsApp agar tim kami segera memprosesnya.</p><a class="order-confirm" data-order-whatsapp href="https://wa.me/6285890007359" target="_blank" rel="noreferrer">Kirim ke WhatsApp <span>↗</span></a></section></form></div></div>
<section class="process section-shell process-light"><div class="process-title"><p class="eyebrow">What you get</p><h2>Strategi yang<br><em>nyambung.</em></h2></div><ol class="process-list"><li><span>01</span><div><h3>Terarah</h3><p>Setiap output punya alasan dan tujuan yang jelas.</p></div></li><li><span>02</span><div><h3>Terukur</h3><p>Kami menyusun indikator untuk melihat kemajuan.</p></div></li><li><span>03</span><div><h3>Terjaga</h3><p>Brand tetap konsisten setelah proyek selesai.</p></div></li></ol></section>
<script type="application/json" id="service-package-data">@json($packages->keyBy('name')->map(fn ($package) => ['basePrice' => $package->base_price, 'price' => $package->final_price, 'discount' => $package->discount_percent]))</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
	const packages = JSON.parse(document.getElementById('service-package-data').textContent);
	const formatPrice = (price) => 'Rp ' + new Intl.NumberFormat('id-ID').format(price);

	document.querySelectorAll('[data-order-package]').forEach(function (button) {
		const packageData = packages[button.dataset.orderPackage];
		if (!packageData) return;

		button.dataset.orderPrice = packageData.price;
		button.dataset.orderBasePrice = packageData.basePrice;
		button.dataset.orderDiscount = packageData.discount;
		const priceElement = button.closest('.package-option, .monthly-service')?.querySelector('.package-price, .monthly-service-price');
		if (priceElement) {
			priceElement.innerHTML = packageData.discount > 0
				? '<span class="price-original">' + formatPrice(packageData.basePrice) + '</span><span class="price-final">' + formatPrice(packageData.price) + '</span><span class="price-discount">Diskon ' + packageData.discount + '%</span>'
				: '<span class="price-final">' + formatPrice(packageData.price) + '</span>';
		}
	});
});
</script>
@endsection
