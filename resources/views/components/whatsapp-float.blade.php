@php
  $whatsappNumber = \App\Models\SiteSetting::get('whatsapp_number');
  $whatsappActive = (string) \App\Models\SiteSetting::get('whatsapp_active', '1') === '1';
  $cleanNumber = $whatsappNumber ? preg_replace('/[^0-9]/', '', $whatsappNumber) : null;
  $defaultMsg = \App\Models\SiteSetting::get('whatsapp_default_message', 'Hello FIX Support, I need assistance with facility services.');
@endphp

@if($whatsappActive && !empty($cleanNumber))
  @php
    $whatsappUrl = 'https://wa.me/' . $cleanNumber . ($defaultMsg ? '?text=' . urlencode($defaultMsg) : '');
  @endphp

  <!-- Floating WhatsApp Support Button -->
  <aside id="fixWhatsAppFloat" class="fix-whatsapp-float" aria-label="WhatsApp Support">
    <a href="{{ $whatsappUrl }}" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="fix-whatsapp-btn" 
       aria-label="Chat with FIX Support on WhatsApp"
       title="Chat on WhatsApp">
      
      <!-- Subtle Pulse Ripple -->
      <span class="fix-whatsapp-pulse" aria-hidden="true"></span>
      
      <!-- WhatsApp Brand Icon -->
      <svg class="fix-whatsapp-icon" xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="#ffffff" viewBox="0 0 16 16" aria-hidden="true">
        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
      </svg>
      
      <!-- Tooltip Label for Desktop Hover -->
      <span class="fix-whatsapp-tooltip">
        <span>Chat on WhatsApp</span>
        <small style="display:block; font-size:0.7rem; opacity:0.85; font-weight:400; color:#38bdf8;">Direct Helpdesk</small>
      </span>
    </a>
  </aside>

  <style>
    .fix-whatsapp-float {
      position: fixed;
      bottom: 28px;
      right: 28px;
      z-index: 99990;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .fix-whatsapp-btn {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 58px;
      height: 58px;
      background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
      color: #ffffff !important;
      border-radius: 50%;
      text-decoration: none;
      box-shadow: 0 4px 18px rgba(37, 211, 102, 0.45), 0 2px 6px rgba(0, 0, 0, 0.15);
      transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
      cursor: pointer;
    }

    .fix-whatsapp-btn:hover {
      transform: scale(1.08) translateY(-3px);
      box-shadow: 0 8px 24px rgba(37, 211, 102, 0.6), 0 4px 10px rgba(0, 0, 0, 0.18);
      color: #ffffff !important;
    }

    .fix-whatsapp-btn:active {
      transform: scale(0.96) translateY(0);
    }

    .fix-whatsapp-icon {
      transition: transform 0.25s ease;
    }

    .fix-whatsapp-btn:hover .fix-whatsapp-icon {
      transform: scale(1.06);
    }

    /* Subtle Animated Pulse Ripple */
    .fix-whatsapp-pulse {
      position: absolute;
      inset: -4px;
      border-radius: 50%;
      border: 2px solid #25D366;
      opacity: 0;
      animation: fixWhatsAppRipple 2.8s infinite cubic-bezier(0.24, 0, 0.38, 1);
      pointer-events: none;
    }

    @keyframes fixWhatsAppRipple {
      0% {
        transform: scale(0.92);
        opacity: 0.8;
      }
      50% {
        transform: scale(1.35);
        opacity: 0;
      }
      100% {
        transform: scale(1.4);
        opacity: 0;
      }
    }

    /* Tooltip styling */
    .fix-whatsapp-tooltip {
      position: absolute;
      right: calc(100% + 14px);
      top: 50%;
      transform: translateY(-50%) translateX(6px);
      background: #0f172a;
      color: #ffffff;
      padding: 7px 14px;
      border-radius: 10px;
      font-size: 0.82rem;
      font-weight: 600;
      line-height: 1.25;
      white-space: nowrap;
      pointer-events: none;
      opacity: 0;
      visibility: hidden;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.35);
      transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
    }

    .fix-whatsapp-tooltip::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 100%;
      transform: translateY(-50%);
      border-width: 6px;
      border-style: solid;
      border-color: transparent transparent transparent #0f172a;
    }

    .fix-whatsapp-btn:hover .fix-whatsapp-tooltip {
      opacity: 1;
      visibility: visible;
      transform: translateY(-50%) translateX(0);
    }

    /* Responsive adjustments for mobile devices */
    @media (max-width: 768px) {
      .fix-whatsapp-float {
        bottom: 20px;
        right: 20px;
      }
      .fix-whatsapp-btn {
        width: 52px;
        height: 52px;
      }
      .fix-whatsapp-icon {
        width: 26px;
        height: 26px;
      }
      .fix-whatsapp-tooltip {
        display: none; /* Hide hover tooltip on touch devices */
      }
    }
  </style>
@endif
