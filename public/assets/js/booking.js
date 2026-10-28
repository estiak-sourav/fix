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
      "Khilgaon / Malibagh", "Old Dhaka / Lalbagh", "Niketan", "Khilkhet",
      "Cantonment", "Shahbagh", "Farmgate", "Pallabi", "Jatrabari"
    ],
    "Gazipur": ["Tongi", "Gazipur Sadar", "Konabari Industrial", "Chowrasta", "Kaliakair", "Sreepur"],
    "Narayanganj": ["Narayanganj City", "Fatullah", "Siddhirganj EPZ", "Kanchpur Industrial", "Sonargaon"],
    "Savar & Ashulia": ["Savar Bazar", "Ashulia EPZ", "Hemayetpur", "DEPZ Area", "Dhamrai"],
    "Narsingdi": ["Narsingdi Sadar", "Madhabdi", "Palash", "Shibpur"],
    "Tangail": ["Tangail Sadar", "Mirzapur", "Kalihati", "Madhupur"],
    "Faridpur": ["Faridpur Sadar", "Bhanga", "Boalmari"],
    "Manikganj": ["Manikganj Sadar", "Singair"],
    "Munshiganj": ["Munshiganj Sadar", "Sreenagar", "Gajaria"],
    "Gopalganj": ["Gopalganj Sadar", "Tungipara"],
    "Madaripur": ["Madaripur Sadar", "Shibchar"],
    "Rajbari": ["Rajbari Sadar", "Goalanda"],
    "Shariatpur": ["Shariatpur Sadar", "Naria"],
    "Kishoreganj": ["Kishoreganj Sadar", "Bhairab"]
  },
  "Chattogram": {
    "Chattogram City": [
      "Agrabad Commercial", "Nasirabad", "GEC Circle", "Khulshi R/A", "Halishahar",
      "Panchlaish", "CEPZ Area", "Kotwali", "Chawkbazar", "Muradpur", "Pahartali", "Patenga"
    ],
    "Cox's Bazar": ["Hotel Motel Zone", "Cox's Bazar Sadar", "Kolatoli", "Teknaf", "Ramu"],
    "Cumilla": ["Kandirpar", "Cumilla Sadar", "Cumilla EPZ Area", "Daudkandi", "Laksham"],
    "Feni": ["Feni Sadar", "Daganbhuiyan", "Chhagalnaiya"],
    "Brahmanbaria": ["Brahmanbaria Sadar", "Ashuganj", "Kasba"],
    "Noakhali": ["Maijdee Town", "Begumganj / Chowmuhani", "Chatkhil"],
    "Chandpur": ["Chandpur Sadar", "Hajiganj", "Faridganj"],
    "Lakshmipur": ["Lakshmipur Sadar", "Raipur", "Ramganj"],
    "Rangamati": ["Rangamati Sadar", "Kaptai"],
    "Khagrachhari": ["Khagrachhari Sadar", "Ramgarh"],
    "Bandarban": ["Bandarban Sadar", "Ruma"]
  },
  "Rajshahi": {
    "Rajshahi City": [
      "Shaheb Bazar", "Boalia", "Kazla", "Motihar", "Rajpara", "Upashahar", "Shiroil"
    ],
    "Bogura": ["Bogura Sadar", "Sherpur", "Santahar", "Shibganj"],
    "Pabna": ["Pabna Sadar", "Ishwardi EPZ", "Santhia"],
    "Sirajganj": ["Sirajganj Sadar", "Ullapara", "Shahjadpur"],
    "Naogaon": ["Naogaon Sadar", "Mohadevpur", "Patnitala"],
    "Natore": ["Natore Sadar", "Singra", "Baraigram"],
    "Chapainawabganj": ["Chapainawabganj Sadar", "Shibganj"],
    "Joypurhat": ["Joypurhat Sadar", "Panchbibi"]
  },
  "Khulna": {
    "Khulna City": [
      "Shibbari", "Boyra", "Khalishpur Industrial", "Daulatpur", "Sonadanga", "Rupsha", "Khan Jahan Ali"
    ],
    "Jashore": ["Jashore Sadar", "Benapole Port Area", "Abhaynagar"],
    "Kushtia": ["Kushtia Sadar", "Bheramara", "Poradah"],
    "Satkhira": ["Satkhira Sadar", "Kalaroa", "Shyamnagar"],
    "Jhenaidah": ["Jhenaidah Sadar", "Kaliganj"],
    "Chuadanga": ["Chuadanga Sadar", "Alamdanga"],
    "Meherpur": ["Meherpur Sadar", "Mujibnagar"],
    "Bagerhat": ["Bagerhat Sadar", "Mongla Port Area"],
    "Magura": ["Magura Sadar", "Sreepur"],
    "Narail": ["Narail Sadar", "Lohagara"]
  },
  "Sylhet": {
    "Sylhet City": [
      "Zindabazar", "Amberkhana", "Shahjalal Uposhohor", "Subidbazar", "Kumarpara",
      "Shibganj", "Tilagarh", "South Surma", "Lamabazar", "Pathantula"
    ],
    "Moulvibazar": ["Sreemangal Tourism Area", "Moulvibazar Sadar", "Kulaura"],
    "Habiganj": ["Habiganj Sadar", "Madhabpur Industrial", "Nabiganj"],
    "Sunamganj": ["Sunamganj Sadar", "Jagannathpur", "Chhatak"]
  },
  "Barishal": {
    "Barishal City": [
      "Barishal Sadar", "Natullabad", "Rupatali", "C&B Road", "Brown Compound", "Launch Ghat Area", "Band Road"
    ],
    "Patuakhali": ["Patuakhali Sadar", "Kuakata Tourism Area", "Galachipa"],
    "Bhola": ["Bhola Sadar", "Borhanuddin", "Char Fasson"],
    "Pirojpur": ["Pirojpur Sadar", "Mathbaria", "Swarupkathi"],
    "Jhalokati": ["Jhalokati Sadar", "Nalchity"],
    "Barguna": ["Barguna Sadar", "Amtali"]
  },
  "Rangpur": {
    "Rangpur City": [
      "Rangpur Sadar", "Jahaj Company Mor", "Carmichael College Area", "Modern Mor", "Medical Mor", "Dhap"
    ],
    "Dinajpur": ["Dinajpur Sadar", "Fulbari", "Parbatipur"],
    "Nilphamari": ["Saidpur Commercial Hub", "Nilphamari Sadar", "Uttara EPZ"],
    "Kurigram": ["Kurigram Sadar", "Ulipur"],
    "Gaibandha": ["Gaibandha Sadar", "Gobindaganj"],
    "Lalmonirhat": ["Lalmonirhat Sadar", "Burimari"],
    "Thakurgaon": ["Thakurgaon Sadar", "Pirganj"],
    "Panchagarh": ["Panchagarh Sadar", "Tetulia"]
  },
  "Mymensingh": {
    "Mymensingh City": [
      "Mymensingh Sadar", "Ganginarpar", "Town Hall Area", "Charpara", "Notun Bazar", "BAU Campus Area", "Trishal", "Bhaluka Industrial"
    ],
    "Jamalpur": ["Jamalpur Sadar", "Sarishabari"],
    "Netrokona": ["Netrokona Sadar", "Mohanganj"],
    "Sherpur": ["Sherpur Sadar", "Nalitabari"]
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
  const categorySelect = document.getElementById("bookCategory") || document.getElementById("bookService");
  const specificServiceSelect = document.getElementById("bookSpecificService");
  const propertySelect = document.getElementById("bookProperty");
  const sidebarServiceName = document.getElementById("sidebarServiceName");
  const sidebarCategoryName = document.getElementById("sidebarCategoryName");
  const sidebarPropertyType = document.getElementById("sidebarPropertyType");

  // Fallback: If not already rendered with server options, populate from catalog mapping
  if (categorySelect && categorySelect.options.length <= 1) {
    categorySelect.innerHTML = `<option value="">-- Choose Required Service --</option>` +
      Object.keys(SERVICE_CATALOG_MAPPING).map(cat => `<option value="${cat}" data-title="${cat}">${cat}</option>`).join('');
  }

  // Read URL Params for pre-fill
  const urlParams = new URLSearchParams(window.location.search);
  const paramService = urlParams.get("service");
  const paramProperty = urlParams.get("property") || urlParams.get("property_type");
  const paramSchedule = urlParams.get("schedule");

  if (paramService && categorySelect) {
    const cleanSearch = paramService.trim().toLowerCase();
    let found = false;
    for (let i = 0; i < categorySelect.options.length; i++) {
      const opt = categorySelect.options[i];
      const optText = (opt.dataset.title || opt.text || "").trim().toLowerCase();
      const optSlug = (opt.dataset.slug || "").trim().toLowerCase();
      const optVal = opt.value.trim().toLowerCase();
      if (optVal === cleanSearch || optText === cleanSearch || optSlug === cleanSearch || optText.includes(cleanSearch) || cleanSearch.includes(optText)) {
        categorySelect.selectedIndex = i;
        found = true;
        populateSpecificServices(opt.dataset.title || opt.text);
        break;
      }
    }
    if (!found) {
      populateSpecificServices(paramService);
    }
  } else if (categorySelect && categorySelect.value) {
    const opt = categorySelect.options[categorySelect.selectedIndex];
    populateSpecificServices(opt ? (opt.dataset.title || opt.text) : categorySelect.value);
  }

  if (paramProperty && propertySelect) {
    const cleanProp = paramProperty.trim().toLowerCase();
    for (let i = 0; i < propertySelect.options.length; i++) {
      const opt = propertySelect.options[i];
      if (opt.value.toLowerCase().includes(cleanProp) || cleanProp.includes(opt.value.toLowerCase())) {
        propertySelect.selectedIndex = i;
        break;
      }
    }
  }

  if (paramSchedule) {
    const timeSlotSelect = document.getElementById("bookTimeSlot");
    const urgencySelect = document.getElementById("bookUrgency");
    const cleanSched = paramSchedule.toLowerCase();
    if (cleanSched.includes("emergency")) {
      if (timeSlotSelect) timeSlotSelect.value = "Emergency (Immediate Dispatch ASAP)";
      if (urgencySelect) urgencySelect.value = "emergency_1h";
    } else if (cleanSched.includes("morning")) {
      if (timeSlotSelect) timeSlotSelect.value = "Morning (9:00 AM - 12:00 PM)";
    } else if (cleanSched.includes("afternoon")) {
      if (timeSlotSelect) timeSlotSelect.value = "Afternoon (12:00 PM - 4:00 PM)";
    } else if (cleanSched.includes("evening")) {
      if (timeSlotSelect) timeSlotSelect.value = "Evening (4:00 PM - 8:00 PM)";
    }
  }

  updateSidebarSummary();

  // Service Change Handler
  categorySelect?.addEventListener("change", () => {
    const opt = categorySelect.options[categorySelect.selectedIndex];
    const srvTitle = opt ? (opt.dataset.title || opt.text) : categorySelect.value;
    populateSpecificServices(srvTitle);
    updateSidebarSummary();
  });

  specificServiceSelect?.addEventListener("change", updateSidebarSummary);
  propertySelect?.addEventListener("change", updateSidebarSummary);

  function populateSpecificServices(serviceTitle) {
    if (!specificServiceSelect) return;
    if (!serviceTitle) {
      specificServiceSelect.innerHTML = `<option value="">Comprehensive Inspection / General Service</option>`;
      return;
    }

    let items = null;
    const cleanTitle = (serviceTitle || "").trim().toLowerCase();
    for (const [key, val] of Object.entries(SERVICE_CATALOG_MAPPING)) {
      if (key.toLowerCase() === cleanTitle || key.toLowerCase().includes(cleanTitle) || cleanTitle.includes(key.toLowerCase())) {
        items = val;
        break;
      }
    }

    if (items && items.length > 0) {
      specificServiceSelect.innerHTML = `<option value="">Comprehensive Inspection / General Service</option>` +
        items.map(item => `<option value="${item}">${item}</option>`).join('');
    } else {
      specificServiceSelect.innerHTML = `<option value="">Comprehensive Inspection / General Service</option>`;
    }
  }

  function updateSidebarSummary() {
    const selectedOpt = categorySelect ? categorySelect.options[categorySelect.selectedIndex] : null;
    const srvTitle = selectedOpt && selectedOpt.value ? (selectedOpt.dataset.title || selectedOpt.text) : "Select a Service";
    const spec = (specificServiceSelect && specificServiceSelect.value) ? specificServiceSelect.value : srvTitle;
    const prop = propertySelect?.value || "Standard Property";

    if (sidebarCategoryName) sidebarCategoryName.textContent = "Service";
    if (sidebarServiceName) sidebarServiceName.textContent = spec;
    if (sidebarPropertyType) sidebarPropertyType.textContent = prop;
  }

  // Form Submission
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const fullName = document.getElementById("bookFullName").value.trim();
    const phone = document.getElementById("bookPhone").value.trim();
    const email = document.getElementById("bookEmail") ? document.getElementById("bookEmail").value.trim() : "";
    const selectedOpt = categorySelect ? categorySelect.options[categorySelect.selectedIndex] : null;
    const serviceId = selectedOpt && selectedOpt.value ? selectedOpt.value : "";
    const serviceTitle = selectedOpt && selectedOpt.value ? (selectedOpt.dataset.title || selectedOpt.text) : "";
    const specificService = (specificServiceSelect && specificServiceSelect.value) ? specificServiceSelect.value : serviceTitle;
    const propertyType = propertySelect.value;
    const date = document.getElementById("bookDate").value;
    const timeSlot = document.getElementById("bookTimeSlot").value;
    const division = document.getElementById("bookDivision").value;
    const district = document.getElementById("bookDistrict").value;
    const area = document.getElementById("bookArea").value;
    const address = document.getElementById("bookAddress").value.trim();
    const problemDesc = document.getElementById("bookProblemDesc").value.trim();

    // Basic Validation
    if (!fullName || !phone || !serviceId || !division || !district || !area || !address) {
      alert("Please fill in all required fields marked with *");
      return;
    }

    // Phone validation (Bangladesh format: 01... or +8801...)
    const cleanPhone = phone.replace(/\s+/g, '');
    if (!/(^(\+8801|8801|01))[3-9]{1}[0-9]{8}$/.test(cleanPhone)) {
      alert("Please provide a valid Bangladeshi phone number (e.g. 01712345678 or +8801712345678)");
      return;
    }

    // Check customer login
    const isUserLoggedIn = (window.FIX_AUTH_USER && window.FIX_AUTH_USER.id) || (typeof window.IS_LOGGED_IN !== 'undefined' && window.IS_LOGGED_IN);
    if (!isUserLoggedIn) {
      if (typeof openAuthModal === "function") {
        openAuthModal();
      } else {
        alert("Please log in with email OTP to submit your service request.");
      }
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

    const formData = new FormData(form);
    formData.set("customer_name", fullName);
    formData.set("customer_phone", cleanPhone);
    formData.set("service_id", serviceId);
    formData.set("property_type", propertyType);
    formData.set("division", division);
    formData.set("district", district);
    formData.set("area", area);
    formData.set("street_address", address);
    formData.set("preferred_date", date);
    formData.set("preferred_time_slot", timeSlot);
    formData.set("urgency", document.getElementById("bookUrgency")?.value || "standard");
    formData.set("problem_description", problemDesc || `Service request for ${serviceTitle}`);
    formData.set("service_type", "on_demand");

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") 
      || document.querySelector('input[name="_token"]')?.value;

    fetch("/booking", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": csrfToken || "",
        "Accept": "application/json"
      },
      body: formData
    })
    .then(async res => {
      const data = await res.json().catch(() => ({}));
      if (res.status === 401 || data.requires_auth) {
        if (typeof openAuthModal === "function") {
          openAuthModal();
        } else {
          alert("Session expired. Please sign in again.");
        }
        return;
      }

      if (!res.ok) {
        throw new Error(data.message || "Failed to submit booking request.");
      }

      const bookingRecord = {
        requestId: data.booking_number || `FIX-${Date.now()}`,
        fullName,
        phone,
        email,
        category: "Service Request",
        service: specificService || serviceTitle,
        propertyType,
        date,
        timeSlot,
        area,
        district
      };

      // Reset form & preview
      form.reset();
      clearPhotoPreview();

      // Show Confirmation Modal
      showBookingSuccessModal(bookingRecord);
    })
    .catch(err => {
      alert(err.message || "Something went wrong while submitting your request.");
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
    });
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

  function populateDistricts(div, selectedDistrict = "") {
    if (!div || !BD_LOCATIONS[div]) {
      districtSelect.disabled = true;
      districtSelect.innerHTML = `<option value="">Select Division First...</option>`;
      areaSelect.disabled = true;
      areaSelect.innerHTML = `<option value="">Select District First...</option>`;
      return;
    }

    const districts = Object.keys(BD_LOCATIONS[div]);
    districtSelect.disabled = false;
    districtSelect.innerHTML = `<option value="">Select District...</option>` +
      districts.map(d => `<option value="${d}" ${d === selectedDistrict ? 'selected' : ''}>${d}</option>`).join('');
  }

  function populateAreas(div, dist, selectedArea = "") {
    if (!div || !dist || !BD_LOCATIONS[div] || !BD_LOCATIONS[div][dist]) {
      areaSelect.disabled = true;
      areaSelect.innerHTML = `<option value="">Select District First...</option>`;
      return;
    }

    const areas = BD_LOCATIONS[div][dist];
    areaSelect.disabled = false;
    areaSelect.innerHTML = `<option value="">Select Thana / Area...</option>` +
      areas.map(a => `<option value="${a}" ${a === selectedArea ? 'selected' : ''}>${a}</option>`).join('') +
      `<option value="Other Area" ${selectedArea === 'Other Area' ? 'selected' : ''}>Other Area / Specific Locality</option>`;
  }

  divisionSelect.addEventListener("change", () => {
    const div = divisionSelect.value;
    populateDistricts(div);
  });

  districtSelect.addEventListener("change", () => {
    const div = divisionSelect.value;
    const dist = districtSelect.value;
    populateAreas(div, dist);
  });

  // Smart Auto-Selection for logged-in user profile
  const user = window.FIX_AUTH_USER || {};
  const userDiv = user.division || "";
  const userDist = user.district || "";
  const userArea = user.area || "";

  if (userDiv && BD_LOCATIONS[userDiv]) {
    divisionSelect.value = userDiv;
    populateDistricts(userDiv, userDist);
    if (userDist && BD_LOCATIONS[userDiv][userDist]) {
      populateAreas(userDiv, userDist, userArea);
    }
  }
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
