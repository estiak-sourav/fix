/**
 * FIX - Building and Facility Maintenance Services
 * Booking Page Interactive Flow
 */

const BD_LOCATIONS = {
  "Dhaka": {
    "Dhaka City": [
      "Gulshan 1 & 2", "Banani", "Baridhara & DOHS", "Bashundhara R/A",
      "Uttara (All Sectors)", "Dhanmondi", "Mohakhali", "Tejgaon Industrial Area",
      "Mirpur (1-14)", "Mohammadpur", "Motijheel Commercial", "Badda / Rampura",
      "Khilgaon / Malibagh", "Old Dhaka / Lalbagh", "Niketan", "Khilkhet"
    ],
    "Gazipur": ["Tongi", "Gazipur Sadar", "Konabari Industrial", "Chowrasta", "Kaliakair"],
    "Narayanganj": ["Narayanganj City", "Fatullah", "Siddhirganj EPZ", "Kanchpur Industrial"],
    "Savar": ["Savar Bazar", "Ashulia EPZ", "Hemayetpur", "DEPZ Area"]
  },
  "Chattogram": {
    "Chattogram City": ["Agrabad Commercial", "Nasirabad", "GEC Circle", "Khulshi R/A", "Halishahar", "Panchlaish", "CEPZ Area"],
    "Cox's Bazar": ["Hotel Motel Zone", "Sadar", "Kolatoli"]
  },
  "Sylhet": {
    "Sylhet City": ["Zindabazar", "Amberkhana", "Shahjalal Uposhohor", "Subidbazar", "Kumarpara"]
  },
  "Rajshahi": {
    "Rajshahi City": ["Shaheb Bazar", "Kazla", "Boalia", "Motihar"]
  },
  "Khulna": {
    "Khulna City": ["Shibbari", "Boyra", "Khalishpur Industrial", "Daulatpur"]
  }
};

const SERVICE_CATALOG_MAPPING = {
  "Mechanical & Electrical": [
    "Complete Electrical Wiring & Inspection",
    "Switchgear & Substation Maintenance",
    "Circuit Breaker & Distribution Board Repair",
    "Lighting Installation & LED Upgrades",
    "Emergency Power & Short Circuit Troubleshooting",
    "Annual Electrical Safety & Earthing Audit"
  ],
  "Plumbing & Sanitary Solutions": [
    "Pipe Leakage Detection & Repair",
    "Bathroom Fixture & Sanitary Installation",
    "Water Booster Pump & Submersible Servicing",
    "Underground & Overhead Water Reservoir Cleaning",
    "Main Water Line Repiping & Pressure Balancing",
    "Drainage & Sewerage Jet Unblocking"
  ],
  "HVAC & Air Conditioning": [
    "Split / Cassette AC Master Jet Servicing",
    "Central VRF / VRV System Maintenance",
    "Industrial Water Chiller & Cooling Tower Care",
    "Air Handling Unit (AHU) & Duct Sanitation",
    "Eco-Friendly Refrigerant Gas Leak Refilling",
    "Corporate Annual HVAC Contract (AMC)"
  ],
  "Commercial & Office Cleaning": [
    "Full-Time Janitorial Facility Staffing",
    "Deep Floor Scrubbing & Marble Polishing",
    "Upholstery, Sofa & Office Chair Shampooing",
    "Exterior Glass Facade Cleaning (Rope Access)",
    "Post-Construction & Move-In Deep Scrub",
    "Carpet Deep Extraction Cleaning"
  ],
  "Clinical & Specialized Sanitization": [
    "Hospital Ward & ICU Terminal Disinfection",
    "Pharmaceutical Cleanroom Bio-Decontamination",
    "Diagnostic Lab Surface & Air Sterilization",
    "Pathogen Barrier Fogging & Infection Control",
    "Medical Grade Waste Handling & Disposal"
  ],
  "Fire Safety & Alarm Systems": [
    "Fire Extinguisher Supply, Inspection & Refill",
    "Fire Hydrant, Hose Reel & Sprinkler Testing",
    "Addressable Smoke & Heat Detector System Setup",
    "Fire Pump House Preventive Maintenance",
    "Evacuation Planning, Fire Drills & BNBC Compliance"
  ],
  "CCTV Installation & Surveillance": [
    "High-Definition Smart IP Camera Installation",
    "Network Video Recorder (NVR) / Server Setup",
    "Biometric Time-Attendance & Turnstile Access",
    "Perimeter Infrared Beam Intrusion Detection",
    "Central Monitoring Control Room Setup"
  ],
  "Generator & Backup Power (IPS)": [
    "Diesel Generator Scheduled Servicing & Overhaul",
    "Automatic Transfer Switch (ATS) Diagnostics",
    "Corporate Industrial UPS & Battery Bank AMC",
    "IPS Installation, Wiring & Battery Replacement"
  ],
  "Swimming Pool Maintenance": [
    "Weekly Chemical Dosing (pH & Chlorine Balancing)",
    "Sand Filter Backwash & Multi-Port Valve Repair",
    "Underwater Algae Scrubbing & Vacuuming",
    "Pool Tile Waterproofing & LED Lighting Repairs"
  ],
  "Landscaping & Greenery Care": [
    "Commercial Lawn Mowing & Landscape Design",
    "Automated Sprinkler & Drip Irrigation Systems",
    "Indoor Plant Maintenance & Air-Purifying Plants",
    "Tree Trimming, Hedge Shaping & Organic Nutrition"
  ],
  "Interior Works & Renovation": [
    "Gypsum Board False Ceiling & Drywall Partition",
    "Architectural Interior & Weatherproof Exterior Paint",
    "Heavy Duty Industrial Epoxy & Tile Flooring",
    "Custom Woodwork, Door & Acoustic Paneling"
  ],
  "Pest Control & Vector Management": [
    "Odorless German Cockroach Gel Treatment",
    "Pre & Post Construction Anti-Termite Chemical Barrier",
    "Rodent Control Stations & Trapping Program",
    "ULV Fogging & Mosquito Vector Eradication"
  ]
};

document.addEventListener("DOMContentLoaded", () => {
  initBookingForm();
  initLocationCascading();
  initPhotoUpload();
});

function initBookingForm() {
  const form = document.getElementById("serviceBookingForm");
  const categorySelect = document.getElementById("bookCategory");
  const specificServiceSelect = document.getElementById("bookSpecificService");
  const propertySelect = document.getElementById("bookProperty");
  const sidebarServiceName = document.getElementById("sidebarServiceName");
  const sidebarCategoryName = document.getElementById("sidebarCategoryName");
  const sidebarPropertyType = document.getElementById("sidebarPropertyType");

  // Populate Categories
  categorySelect.innerHTML = `<option value="">Select Service Category...</option>` +
    Object.keys(SERVICE_CATALOG_MAPPING).map(cat => `<option value="${cat}">${cat}</option>`).join('');

  // Read URL Params for pre-fill
  const urlParams = new URLSearchParams(window.location.search);
  const paramService = urlParams.get("service");
  const paramProperty = urlParams.get("property");

  if (paramService && SERVICE_CATALOG_MAPPING[paramService]) {
    categorySelect.value = paramService;
    populateSpecificServices(paramService);
  }

  if (paramProperty && propertySelect) {
    propertySelect.value = paramProperty;
    if (sidebarPropertyType) sidebarPropertyType.textContent = paramProperty;
  }

  // Category Change Handler
  categorySelect.addEventListener("change", () => {
    const selected = categorySelect.value;
    populateSpecificServices(selected);
    updateSidebarSummary();
  });

  specificServiceSelect.addEventListener("change", updateSidebarSummary);
  propertySelect?.addEventListener("change", updateSidebarSummary);

  function populateSpecificServices(category) {
    if (!category || !SERVICE_CATALOG_MAPPING[category]) {
      specificServiceSelect.innerHTML = `<option value="">First choose a Category above</option>`;
      specificServiceSelect.disabled = true;
      return;
    }

    const items = SERVICE_CATALOG_MAPPING[category];
    specificServiceSelect.disabled = false;
    specificServiceSelect.innerHTML = `<option value="">Select Specific Service (${category})...</option>` +
      items.map(item => `<option value="${item}">${item}</option>`).join('');
  }

  function updateSidebarSummary() {
    const cat = categorySelect.value || "Select a category";
    const spec = specificServiceSelect.value || "General Service Request";
    const prop = propertySelect?.value || "Standard Property";

    if (sidebarCategoryName) sidebarCategoryName.textContent = cat;
    if (sidebarServiceName) sidebarServiceName.textContent = spec;
    if (sidebarPropertyType) sidebarPropertyType.textContent = prop;
  }

  // Form Submission
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const fullName = document.getElementById("bookFullName").value.trim();
    const phone = document.getElementById("bookPhone").value.trim();
    const email = document.getElementById("bookEmail").value.trim();
    const category = categorySelect.value;
    const specificService = specificServiceSelect.value || category;
    const propertyType = propertySelect.value;
    const date = document.getElementById("bookDate").value;
    const timeSlot = document.getElementById("bookTimeSlot").value;
    const frequency = document.getElementById("bookEngagementModel")?.value || document.querySelector('input[name="bookFrequency"]:checked')?.value || "One-Time";
    const division = document.getElementById("bookDivision").value;
    const district = document.getElementById("bookDistrict").value;
    const area = document.getElementById("bookArea").value;
    const address = document.getElementById("bookAddress").value.trim();
    const problemDesc = document.getElementById("bookProblemDesc").value.trim();

    // Basic Validation
    if (!fullName || !phone || !category || !division || !district || !area || !address) {
      alert("Please fill in all required fields marked with *");
      return;
    }

    // Phone validation (Bangladesh format: 01... or +8801...)
    const cleanPhone = phone.replace(/\s+/g, '');
    if (!/(^(\+8801|8801|01))[3-9]{1}[0-9]{8}$/.test(cleanPhone)) {
      alert("Please provide a valid Bangladeshi phone number (e.g. 01712345678 or +8801712345678)");
      return;
    }

    // Button loader state
    const submitBtn = form.querySelector("button[type='submit']");
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
      <svg class="spin" style="width:20px;height:20px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <circle cx="12" cy="12" r="10" stroke-width="4" stroke="currentColor" stroke-dasharray="32" stroke-linecap="round"></circle>
      </svg>
      Processing Service Request...
    `;

    setTimeout(() => {
      // Generate Unique Request ID
      const randomNum = Math.floor(10000 + Math.random() * 90000);
      const requestId = `SR-${randomNum}`;

      const bookingRecord = {
        requestId,
        timestamp: new Date().toISOString(),
        fullName,
        phone,
        email,
        category,
        service: specificService,
        propertyType,
        date,
        timeSlot,
        frequency,
        division,
        district,
        area,
        address,
        problemDesc,
        status: "Confirmed & In Review"
      };

      // Store in localStorage for demo review
      const existing = JSON.parse(localStorage.getItem("fix_bookings") || "[]");
      existing.push(bookingRecord);
      localStorage.setItem("fix_bookings", JSON.stringify(existing));

      // Restore button
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;

      // Reset form
      form.reset();
      clearPhotoPreview();

      // Show Confirmation Modal
      showBookingSuccessModal(bookingRecord);
    }, 1200);
  });
}

function initLocationCascading() {
  const divisionSelect = document.getElementById("bookDivision");
  const districtSelect = document.getElementById("bookDistrict");
  const areaSelect = document.getElementById("bookArea");

  if (!divisionSelect || !districtSelect || !areaSelect) return;

  // Populate Divisions
  divisionSelect.innerHTML = `<option value="">Select Division...</option>` +
    Object.keys(BD_LOCATIONS).map(div => `<option value="${div}">${div}</option>`).join('');

  divisionSelect.addEventListener("change", () => {
    const div = divisionSelect.value;
    districtSelect.disabled = !div;
    areaSelect.disabled = true;
    areaSelect.innerHTML = `<option value="">Select District First...</option>`;

    if (!div || !BD_LOCATIONS[div]) {
      districtSelect.innerHTML = `<option value="">Select Division First...</option>`;
      return;
    }

    const districts = Object.keys(BD_LOCATIONS[div]);
    districtSelect.innerHTML = `<option value="">Select District...</option>` +
      districts.map(d => `<option value="${d}">${d}</option>`).join('');
  });

  districtSelect.addEventListener("change", () => {
    const div = divisionSelect.value;
    const dist = districtSelect.value;
    areaSelect.disabled = !dist;

    if (!div || !dist || !BD_LOCATIONS[div][dist]) {
      areaSelect.innerHTML = `<option value="">Select District First...</option>`;
      return;
    }

    const areas = BD_LOCATIONS[div][dist];
    areaSelect.innerHTML = `<option value="">Select Thana / Area...</option>` +
      areas.map(a => `<option value="${a}">${a}</option>`).join('') +
      `<option value="Other Area">Other Area / Specific Locality</option>`;
  });
}

function initPhotoUpload() {
  const uploadInput = document.getElementById("problemPhotoInput");
  const previewContainer = document.getElementById("photoPreviewBox");

  if (!uploadInput || !previewContainer) return;

  uploadInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (file) {
      if (file.size > 5 * 1024 * 1024) {
        alert("File size exceeds 5MB limit. Please choose a smaller photo.");
        uploadInput.value = "";
        return;
      }

      const reader = new FileReader();
      reader.onload = (event) => {
        previewContainer.style.display = "flex";
        previewContainer.innerHTML = `
          <img src="${event.target.result}" alt="Preview" class="preview-thumb">
          <div style="flex:1;">
            <div style="font-weight:600;font-size:0.875rem;">${file.name}</div>
            <div style="font-size:0.75rem;color:var(--text-muted);">${(file.size / 1024).toFixed(1)} KB</div>
          </div>
          <button type="button" id="removePhotoBtn" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:0.85rem;font-weight:600;">
            Remove
          </button>
        `;

        document.getElementById("removePhotoBtn").addEventListener("click", clearPhotoPreview);
      };
      reader.readAsDataURL(file);
    }
  });
}

function clearPhotoPreview() {
  const uploadInput = document.getElementById("problemPhotoInput");
  const previewContainer = document.getElementById("photoPreviewBox");
  if (uploadInput) uploadInput.value = "";
  if (previewContainer) {
    previewContainer.innerHTML = "";
    previewContainer.style.display = "none";
  }
}

function showBookingSuccessModal(record) {
  const modal = document.getElementById("bookingConfirmationModal");
  if (!modal) return;

  const reqIdSpan = modal.querySelector(".modal-req-id");
  const clientNameSpan = modal.querySelector(".modal-client-name");
  const serviceSpan = modal.querySelector(".modal-client-service");
  const dateSpan = modal.querySelector(".modal-client-date");
  const locationSpan = modal.querySelector(".modal-client-location");

  if (reqIdSpan) reqIdSpan.textContent = record.requestId;
  if (clientNameSpan) clientNameSpan.textContent = record.fullName;
  if (serviceSpan) serviceSpan.textContent = record.service;
  if (dateSpan) dateSpan.textContent = `${record.date || 'Earliest Available'} (${record.timeSlot})`;
  if (locationSpan) locationSpan.textContent = `${record.area}, ${record.district}`;

  modal.classList.add("active");

  const closeBtns = modal.querySelectorAll(".modal-close-btn");
  closeBtns.forEach(btn => {
    btn.onclick = () => modal.classList.remove("active");
  });

  modal.onclick = (e) => {
    if (e.target === modal) modal.classList.remove("active");
  };
}
