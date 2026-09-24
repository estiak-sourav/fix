/**
 * FIX - Building and Facility Maintenance Services
 * Core JavaScript Functionality
 */

// Services Data Store
const FIX_SERVICES = [
  {
    id: "electrical",
    title: "Mechanical & Electrical",
    category: "maintenance",
    tag: "Facility Maintenance",
    image: "https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=800&q=80",
    desc: "Complete electrical maintenance, power distribution, wiring, circuit breakers, and lighting solutions for commercial and residential facilities.",
    features: [
      "Switchgear & Distribution Panels",
      "Wiring & Lighting Installation",
      "Emergency Power Troubleshooting",
      "Thermal Scanning & Safety Audit"
    ],
    details: {
      residential: "Apartment rewiring, lighting fixtures, short circuit repair, load calculation, earthing inspection.",
      commercial: "Substation maintenance, industrial switchboards, 3-phase power balancing, scheduled safety audits.",
      sla: "Emergency dispatch within 45-60 minutes in Dhaka metro."
    }
  },
  {
    id: "plumbing",
    title: "Plumbing & Sanitary Solutions",
    category: "maintenance",
    tag: "Facility Maintenance",
    image: "https://images.unsplash.com/photo-1585704032915-c3400ca199e7?auto=format&fit=crop&w=800&q=80",
    desc: "Precision leak detection, high-pressure piping, bathroom fittings, submersible pump servicing, and underground reservoir cleaning.",
    features: [
      "Acoustic Leak Detection",
      "Water Pump & Booster Systems",
      "Sanitary & Bathroom Overhaul",
      "Reservoir Deep Disinfection"
    ],
    details: {
      residential: "Faucet/commode fixing, pipe burst emergency, water heater setup, kitchen sink drainage clearing.",
      commercial: "Commercial water line design, grease trap clearing, pressure boosting systems, automated level sensors.",
      sla: "Prompt 60-minute dispatch for water flooding or pump failure."
    }
  },
  {
    id: "hvac",
    title: "HVAC & Air Conditioning",
    category: "maintenance",
    tag: "Facility Maintenance",
    image: "https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=800&q=80",
    desc: "Chiller maintenance, VRF/Central HVAC engineering, split & cassette AC servicing, air duct sanitation, and eco-refrigerant replenishment.",
    features: [
      "Commercial Chillers & VRF",
      "Split & Cassette AC Servicing",
      "Air Duct Deep Cleaning",
      "Refrigerant Leak Diagnostics"
    ],
    details: {
      residential: "Jet wash master servicing, gas top-up, PCB board repair, indoor coil chemical wash.",
      commercial: "Central plant cooling towers, AHU/FCU maintenance, air balancing, comprehensive summer AMC.",
      sla: "Same-day servicing & 24/7 breakdown callouts for server rooms."
    }
  },
  {
    id: "cleaning",
    title: "Commercial & Office Cleaning",
    category: "cleaning",
    tag: "Cleaning Services",
    image: "https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=800&q=80",
    desc: "Daily office janitorial programs, industrial floor scrubbing, carpet shampooing, glass facade washing, and post-construction scrubbing.",
    features: [
      "Daily Corporate Janitorial Staff",
      "Deep Carpet & Chair Shampooing",
      "High-Pressure Floor Scrubbing",
      "Exterior Glass Facade Cleaning"
    ],
    details: {
      residential: "Move-in deep scrub, sofa & mattress steam sterilization, kitchen degreasing, bathroom descaling.",
      commercial: "Trained uniformed cleaners, green eco-certified chemicals, automated scrubbers, waste disposal management.",
      sla: "Flexible morning, evening, or night shift deployments."
    }
  },
  {
    id: "clinical",
    title: "Clinical & Specialized Sanitization",
    category: "cleaning",
    tag: "Cleaning Services",
    image: "https://images.unsplash.com/photo-1584744982491-665216d95f8b?auto=format&fit=crop&w=800&q=80",
    desc: "Hospital-grade disinfection, cleanrooms, medical laboratories, and pharmaceutical facilities following stringent biosafety standards.",
    features: [
      "ICU & Operating Theater Sanitation",
      "Pharma Cleanroom Decontamination",
      "Pathogen Barrier Fogging",
      "Bio-Waste Handling Protocols"
    ],
    details: {
      residential: "Post-illness sanitization, mold eradication, nursery disinfection.",
      commercial: "Compliance with DGHS & hospital accreditation hygiene criteria, certified sanitizers.",
      sla: "24-hour scheduled sanitation cycles with air quality verification."
    }
  },
  {
    id: "fire_safety",
    title: "Fire Safety & Alarm Systems",
    category: "security",
    tag: "Security & Safety",
    image: "https://images.unsplash.com/photo-1599839575945-a9e5af0c3fa5?auto=format&fit=crop&w=800&q=80",
    desc: "Certified inspection, refilling, sprinkler network testing, addressable smoke alarm troubleshooting, and regulatory safety compliance.",
    features: [
      "Extinguisher Hydro-Testing & Refill",
      "Fire Hydrant & Pump Testing",
      "Smoke & Heat Detector Audits",
      "Evacuation Drills & Compliance"
    ],
    details: {
      residential: "Home smoke alarms, kitchen fire blankets, extinguisher maintenance, stairwell route checks.",
      commercial: "NFPA & BNBC standard compliance, automated sprinkler testing, emergency lighting systems, certification assistance.",
      sla: "Quarterly and bi-annual compliance audit contracts."
    }
  },
  {
    id: "cctv",
    title: "CCTV Installation & Surveillance",
    category: "security",
    tag: "Security & Safety",
    image: "https://images.unsplash.com/photo-1557597774-9d273605dfa9?auto=format&fit=crop&w=800&q=80",
    desc: "High-definition IP surveillance, NVR/DVR cloud integration, biometric access control, perimeter intrusion barriers, and smart monitoring.",
    features: [
      "AI-Powered Smart IP Cameras",
      "Biometric Access & Turnstiles",
      "Perimeter Intrusion Sensors",
      "Central Control Room Integration"
    ],
    details: {
      residential: "Smart video doorbells, parking surveillance, remote mobile app monitoring.",
      commercial: "Multi-floor structured cabling, fiber-optic backbone, automated license plate recognition (ANPR), cloud backups.",
      sla: "Rapid camera fault troubleshooting within 2 hours."
    }
  },
  {
    id: "generator",
    title: "Generator & Backup Power (IPS)",
    category: "maintenance",
    tag: "Facility Maintenance",
    image: "https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80",
    desc: "Diesel generator routine overhauls, Automatic Transfer Switch (ATS) repairs, industrial UPS diagnostics, and battery health management.",
    features: [
      "Diesel Generator Servicing & Filters",
      "ATS (Auto Transfer Switch) Testing",
      "Industrial UPS & Battery Banks",
      "Fuel Quality & Exhaust Audits"
    ],
    details: {
      residential: "IPS battery replacement, water refilling, inverter repair, changeover switch replacement.",
      commercial: "50 kVA - 1500 kVA generator AMC, radiator descaling, alternator maintenance, carbon soot clearing.",
      sla: "Priority emergency support 24/7."
    }
  },
  {
    id: "pool",
    title: "Swimming Pool Maintenance",
    category: "maintenance",
    tag: "Facility Maintenance",
    image: "https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?auto=format&fit=crop&w=800&q=80",
    desc: "Automated filtration servicing, chemical balancing (pH & chlorine), underwater scrubbing, leak repairs, and seasonal pool management.",
    features: [
      "Daily / Weekly Chemical Balancing",
      "Sand Filter & Pump Maintenance",
      "Algae Shock & Vacuum Cleaning",
      "Tile Waterproofing & Underwater Lights"
    ],
    details: {
      residential: "Rooftop infinity pool cleaning, robotic vacuum servicing, winterization, water clarity guarantee.",
      commercial: "Hotel, resort, and sports club pools adhering to public health water safety standards.",
      sla: "Scheduled recurring twice-weekly or daily maintenance."
    }
  },
  {
    id: "landscaping",
    title: "Landscaping & Greenery Care",
    category: "maintenance",
    tag: "Facility Maintenance",
    image: "https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?auto=format&fit=crop&w=800&q=80",
    desc: "Commercial turf management, vertical gardens, automated drip irrigation, seasonal trimming, and indoor air-purifying plant care.",
    features: [
      "Lawn Turfing & Periodic Mowing",
      "Automated Irrigation Systems",
      "Indoor Plant Care & Living Walls",
      "Tree Trimming & Organic Fertilizing"
    ],
    details: {
      residential: "Balcony gardens, rooftop terrace lawns, hedge shaping, organic pest shielding.",
      commercial: "Corporate campus grounds, EPZ industrial green zones, hotel courtyard aesthetics.",
      sla: "Dedicated gardener schedules with monthly soil nutrition reports."
    }
  },
  {
    id: "renovation",
    title: "Interior Works & Renovation",
    category: "renovation",
    tag: "Interior & Renovation",
    image: "https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80",
    desc: "Turnkey architectural renovation, gypsum false ceilings, drywall partitions, acoustic dampening, epoxy flooring, and premium painting.",
    features: [
      "Office Partitioning & Ceilings",
      "Interior & Exterior Premium Paint",
      "Epoxy, Vinyl & Tile Flooring",
      "Custom Cabinetry & Carpentry"
    ],
    details: {
      residential: "Kitchen remodeling, waterproof bathroom tile work, custom wardrobes, wall damp proofing.",
      commercial: "Corporate office fit-out, acoustic meeting rooms, reception revamp, durable commercial coatings.",
      sla: "Milestone-based project management with zero cost overruns."
    }
  },
  {
    id: "pest_control",
    title: "Pest Control & Vector Management",
    category: "cleaning",
    tag: "Hygiene & Safety",
    image: "https://images.unsplash.com/photo-1584820927498-cfe5211fd8bf?auto=format&fit=crop&w=800&q=80",
    desc: "Odorless German gel baiting, anti-termite soil barriers, rodent exclusion, and ULV cold fogging for pathogen & mosquito eradication.",
    features: [
      "Odorless Gel Baiting (Cockroaches)",
      "Pre/Post Construction Anti-Termite",
      "Rodent Bait Station Infrastructure",
      "ULV Cold Fogging & Mosquito Control"
    ],
    details: {
      residential: "Safe around children and pets, 6-month warranty on bedbugs and German cockroaches.",
      commercial: "HACCP compliant pest management for food processing, restaurant kitchens, and corporate offices.",
      sla: "Guaranteed eradication with follow-up re-inspection within 14 days."
    }
  }
];

// Document Ready Initialization
document.addEventListener("DOMContentLoaded", () => {
  initNavbar();
  initServiceTabs();
  initServiceModal();
  initQuickBookingWidget();
  initFaqAccordion();
  initContactForm();
  initDemoStorageBadge();
});

/* ==========================================================================
   Navbar & Mobile Menu
   ========================================================================== */
function initNavbar() {
  const navbar = document.querySelector(".navbar");
  const menuToggle = document.querySelector(".menu-toggle");
  const mobileDrawer = document.querySelector(".mobile-drawer");
  const drawerOverlay = document.querySelector(".drawer-overlay");
  const drawerClose = document.querySelector(".drawer-close");
  const drawerLinks = document.querySelectorAll(".drawer-link");

  // Sticky Scroll Class
  window.addEventListener("scroll", () => {
    if (window.scrollY > 20) {
      navbar?.classList.add("scrolled");
    } else {
      navbar?.classList.remove("scrolled");
    }
  });

  // Mobile Drawer Toggle
  function openDrawer() {
    mobileDrawer?.classList.add("active");
    drawerOverlay?.classList.add("active");
    document.body.style.overflow = "hidden";
  }

  function closeDrawer() {
    mobileDrawer?.classList.remove("active");
    drawerOverlay?.classList.remove("active");
    document.body.style.overflow = "";
  }

  menuToggle?.addEventListener("click", openDrawer);
  drawerClose?.addEventListener("click", closeDrawer);
  drawerOverlay?.addEventListener("click", closeDrawer);

  drawerLinks.forEach(link => {
    link.addEventListener("click", closeDrawer);
  });
}

/* ==========================================================================
   Service Tabs & Filter
   ========================================================================== */
function initServiceTabs() {
  const tabBtns = document.querySelectorAll(".tab-btn");
  const servicesGrid = document.querySelector(".services-grid");

  if (!servicesGrid) return;

  // Render initial services
  renderServices("all");

  tabBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      tabBtns.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      const category = btn.getAttribute("data-category") || "all";
      renderServices(category);
    });
  });
}

function renderServices(category) {
  const servicesGrid = document.querySelector(".services-grid");
  if (!servicesGrid) return;

  const filtered = category === "all" 
    ? FIX_SERVICES 
    : FIX_SERVICES.filter(s => s.category === category);

  servicesGrid.innerHTML = filtered.map(service => `
    <article class="service-card" data-id="${service.id}">
      <div class="service-card-image">
        <img src="${service.image}" alt="${service.title}" loading="lazy">
        <span class="service-category-tag">${service.tag}</span>
        <div class="service-icon-bubble">
          ${getServiceIcon(service.id)}
        </div>
      </div>
      <div class="service-card-body">
        <h3 class="service-card-title">${service.title}</h3>
        <ul class="service-features-list">
          ${service.features.map(f => `
            <li>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <polyline points="20 6 9 17 4 12"></polyline>
              </svg>
              ${f}
            </li>
          `).join('')}
        </ul>
        <div class="service-card-footer">
          <button type="button" class="btn btn-outline btn-sm view-service-btn" data-id="${service.id}">
            View Details
          </button>
          <a href="booking.html?service=${encodeURIComponent(service.title)}" class="btn btn-accent btn-sm">
            Book Now
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <line x1="5" y1="12" x2="19" y2="12"></line>
              <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
          </a>
        </div>
      </div>
    </article>
  `).join('');

  // Rebind view details buttons
  document.querySelectorAll(".view-service-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.getAttribute("data-id");
      openServiceModal(id);
    });
  });
}

/* ==========================================================================
   Service Details Modal
   ========================================================================== */
function initServiceModal() {
  const modalOverlay = document.getElementById("serviceDetailsModal");
  const modalClose = modalOverlay?.querySelector(".modal-close-btn");

  modalClose?.addEventListener("click", () => {
    modalOverlay.classList.remove("active");
  });

  modalOverlay?.addEventListener("click", (e) => {
    if (e.target === modalOverlay) {
      modalOverlay.classList.remove("active");
    }
  });
}

function openServiceModal(serviceId) {
  const service = FIX_SERVICES.find(s => s.id === serviceId);
  if (!service) return;

  const modal = document.getElementById("serviceDetailsModal");
  if (!modal) return;

  const modalTitle = modal.querySelector(".modal-service-title");
  const modalImg = modal.querySelector(".modal-service-img");
  const modalDesc = modal.querySelector(".modal-service-desc");
  const modalResScope = modal.querySelector(".modal-residential-scope");
  const modalCommScope = modal.querySelector(".modal-commercial-scope");
  const modalSla = modal.querySelector(".modal-sla-text");
  const modalBookBtn = modal.querySelector(".modal-book-cta");

  if (modalTitle) modalTitle.textContent = service.title;
  if (modalImg) modalImg.src = service.image;
  if (modalDesc) modalDesc.textContent = service.desc;
  if (modalResScope) modalResScope.textContent = service.details.residential;
  if (modalCommScope) modalCommScope.textContent = service.details.commercial;
  if (modalSla) modalSla.textContent = service.details.sla;

  if (modalBookBtn) {
    modalBookBtn.href = `booking.html?service=${encodeURIComponent(service.title)}`;
  }

  modal.classList.add("active");
}

/* ==========================================================================
   Quick Booking Widget
   ========================================================================== */
function initQuickBookingWidget() {
  const quickForm = document.getElementById("quickBookForm");
  const categorySelect = document.getElementById("quickCategory");
  const serviceSelect = document.getElementById("quickService");

  if (!quickForm || !categorySelect || !serviceSelect) return;

  // Populate services based on category
  function updateServices() {
    const selectedCat = categorySelect.value;
    const filtered = selectedCat === "all"
      ? FIX_SERVICES
      : FIX_SERVICES.filter(s => s.category === selectedCat);

    serviceSelect.innerHTML = `<option value="">Select Service...</option>` +
      filtered.map(s => `<option value="${s.title}">${s.title}</option>`).join('');
  }

  categorySelect.addEventListener("change", updateServices);
  updateServices();

  quickForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const service = serviceSelect.value;
    const property = document.getElementById("quickProperty")?.value || "";
    
    let targetUrl = "booking.html";
    const params = new URLSearchParams();
    if (service) params.set("service", service);
    if (property) params.set("property", property);

    if (params.toString()) {
      targetUrl += `?${params.toString()}`;
    }
    window.location.href = targetUrl;
  });
}

/* ==========================================================================
   FAQ Accordion
   ========================================================================== */
function initFaqAccordion() {
  const faqItems = document.querySelectorAll(".faq-item");

  faqItems.forEach(item => {
    const questionBtn = item.querySelector(".faq-question");
    questionBtn?.addEventListener("click", () => {
      const isActive = item.classList.contains("active");

      // Close other FAQs
      faqItems.forEach(other => other.classList.remove("active"));

      // Toggle clicked
      if (!isActive) {
        item.classList.add("active");
      }
    });
  });
}

/* ==========================================================================
   Contact Form Handling
   ========================================================================== */
function initContactForm() {
  const form = document.getElementById("homeContactForm");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const submitBtn = form.querySelector("button[type='submit']");
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = `Sending Message...`;

    setTimeout(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
      form.reset();
      showToast("Thank you! Your message has been sent to FIX Support. We will reach you shortly.");
    }, 1000);
  });
}

/* ==========================================================================
   Demo Request Drawer & LocalStorage Manager
   ========================================================================== */
function initDemoStorageBadge() {
  const trigger = document.getElementById("demoRequestsTrigger");
  const modal = document.getElementById("demoRequestsModal");
  const closeBtn = modal?.querySelector(".modal-close-btn");
  const listContainer = document.getElementById("demoRequestsList");

  if (!trigger || !modal) return;

  function renderList() {
    const bookings = JSON.parse(localStorage.getItem("fix_bookings") || "[]");
    const vendors = JSON.parse(localStorage.getItem("fix_vendors") || "[]");

    if (bookings.length === 0 && vendors.length === 0) {
      listContainer.innerHTML = `
        <div style="text-align:center; padding: 2rem; color: var(--text-muted);">
          <p>No demo submissions yet.</p>
          <p style="font-size:0.85rem;">Submit a test <strong>Service Booking</strong> or <strong>Vendor Registration</strong> to see stored prototype requests here!</p>
        </div>
      `;
      return;
    }

    let html = `<h4>Recent Service Bookings (${bookings.length})</h4>`;
    if (bookings.length === 0) {
      html += `<p style="font-size:0.85rem; color:var(--text-muted);">No bookings yet.</p>`;
    } else {
      bookings.slice(-5).reverse().forEach(b => {
        html += `
          <div class="request-item-row">
            <div>
              <strong style="color:var(--primary);">${b.requestId}</strong> - <span>${b.service}</span>
              <div style="font-size:0.8rem; color:var(--text-muted);">${b.fullName} • ${b.phone} • ${b.area || b.district}</div>
            </div>
            <span class="badge badge-accent">${b.status || 'Pending'}</span>
          </div>
        `;
      });
    }

    html += `<h4 style="margin-top:1.5rem;">Vendor Applications (${vendors.length})</h4>`;
    if (vendors.length === 0) {
      html += `<p style="font-size:0.85rem; color:var(--text-muted);">No vendor applications yet.</p>`;
    } else {
      vendors.slice(-5).reverse().forEach(v => {
        html += `
          <div class="request-item-row">
            <div>
              <strong style="color:var(--primary);">${v.appId}</strong> - <span>${v.vendorName}</span>
              <div style="font-size:0.8rem; color:var(--text-muted);">${v.contactPerson} • ${v.phone} • ${v.services?.join(', ')}</div>
            </div>
            <span class="badge badge-primary">${v.status || 'Under Review'}</span>
          </div>
        `;
      });
    }

    listContainer.innerHTML = html;
  }

  trigger.addEventListener("click", () => {
    renderList();
    modal.classList.add("active");
  });

  closeBtn?.addEventListener("click", () => {
    modal.classList.remove("active");
  });

  modal.addEventListener("click", (e) => {
    if (e.target === modal) modal.classList.remove("active");
  });
}

// Toast Utility
function showToast(message) {
  let container = document.querySelector(".toast-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "toast-container";
    document.body.appendChild(container);
  }

  const toast = document.createElement("div");
  toast.className = "toast";
  toast.innerHTML = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2.5">
      <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
      <polyline points="22 4 12 14.01 9 11.01"></polyline>
    </svg>
    <span>${message}</span>
  `;

  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = "0";
    toast.style.transform = "translateX(100%)";
    toast.style.transition = "all 0.3s ease";
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

// Helpers
function getServiceIcon(id) {
  switch (id) {
    case 'electrical':
      return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>`;
    case 'plumbing':
      return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>`;
    case 'hvac':
      return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"></path></svg>`;
    case 'cleaning':
    case 'clinical':
      return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>`;
    case 'fire_safety':
      return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 2.5z"></path></svg>`;
    case 'cctv':
      return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>`;
    default:
      return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>`;
  }
}
