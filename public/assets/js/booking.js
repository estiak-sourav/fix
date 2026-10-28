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
    const serviceId = selectedOpt && selectedOpt.value ? selectedOpt.value : "";
    const srvTitle = selectedOpt && selectedOpt.value ? (selectedOpt.dataset.title || selectedOpt.text) : "Select a Service";
    const spec = (specificServiceSelect && specificServiceSelect.value) ? specificServiceSelect.value : srvTitle;
    const prop = propertySelect?.value || "Standard Property";

    // 1. Sidebar core text
    if (sidebarCategoryName) sidebarCategoryName.textContent = "Service";
    if (sidebarServiceName) sidebarServiceName.textContent = spec;
    if (sidebarPropertyType) sidebarPropertyType.textContent = prop;

    // 2. Searchable Dropdown Trigger UI
    const triggerText = document.getElementById("selectedServiceText");
    const triggerThumb = document.getElementById("selectedServiceThumb");
    const triggerThumbImg = document.getElementById("selectedServiceThumbImg");

    if (triggerText) {
      triggerText.textContent = (serviceId && srvTitle !== "Select a Service") ? srvTitle : "-- Choose Required Service --";
    }

    const srvData = (window.FIX_SERVICES_DATA && serviceId) ? window.FIX_SERVICES_DATA[serviceId] : null;
    const thumbUrl = srvData?.thumbnail_url || selectedOpt?.dataset?.thumbnail;

    if (triggerThumb && triggerThumbImg) {
      if (thumbUrl && serviceId) {
        triggerThumbImg.src = thumbUrl;
        triggerThumb.style.display = "block";
      } else {
        triggerThumb.style.display = "none";
      }
    }

    // 3. Dynamic Service Details in Sidebar Summary
    const sidebarImgWrapper = document.getElementById("sidebarServiceImageWrapper");
    const sidebarImg = document.getElementById("sidebarServiceImage");
    const sidebarDesc = document.getElementById("sidebarServiceDescription");
    const sidebarMetaBox = document.getElementById("sidebarServiceMetaBox");
    const sidebarSla = document.getElementById("sidebarServiceSla");
    const sidebarPrice = document.getElementById("sidebarServicePrice");

    if (srvData && serviceId) {
      if (sidebarImgWrapper && sidebarImg && srvData.thumbnail_url) {
        sidebarImg.src = srvData.thumbnail_url;
        sidebarImgWrapper.style.display = "block";
      } else if (sidebarImgWrapper) {
        sidebarImgWrapper.style.display = "none";
      }

      if (sidebarDesc) {
        const descText = srvData.subtitle || srvData.description || "";
        if (descText) {
          sidebarDesc.textContent = descText;
          sidebarDesc.style.display = "block";
        } else {
          sidebarDesc.style.display = "none";
        }
      }

      if (sidebarMetaBox) {
        sidebarMetaBox.style.display = "flex";
        if (sidebarSla) {
          sidebarSla.textContent = (srvData.sla_hours || 4) + " Hours";
        }
        if (sidebarPrice) {
          if (srvData.price_starting && Number(srvData.price_starting) > 0) {
            sidebarPrice.textContent = "৳" + Number(srvData.price_starting).toLocaleString();
          } else {
            sidebarPrice.textContent = srvData.pricing_unit || "Quote";
          }
        }
      }
    } else {
      if (sidebarImgWrapper) sidebarImgWrapper.style.display = "none";
      if (sidebarDesc) sidebarDesc.style.display = "none";
      if (sidebarMetaBox) sidebarMetaBox.style.display = "none";
    }
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

    // Clean up automatic file fields and attach our tracked multiple files
    formData.delete("problem_photos[]");
    formData.delete("problem_photos");
    formData.delete("problem_photo");

    if (window.FIX_UPLOADED_PROBLEM_FILES && window.FIX_UPLOADED_PROBLEM_FILES.length > 0) {
      window.FIX_UPLOADED_PROBLEM_FILES.forEach(file => {
        formData.append("problem_photos[]", file);
      });
    }

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

// Global Searchable Dropdown Helpers
window.toggleServiceDropdown = function() {
  const menu = document.getElementById("serviceDropdownMenu");
  if (!menu) return;
  const isHidden = menu.style.display === "none" || !menu.style.display;
  menu.style.display = isHidden ? "block" : "none";
  if (isHidden) {
    const searchInput = document.getElementById("serviceSearchInput");
    if (searchInput) {
      searchInput.value = "";
      searchInput.focus();
      if (typeof window.filterServicesInDropdown === "function") {
        window.filterServicesInDropdown("");
      }
    }
  }
};

window.filterServicesInDropdown = function(query) {
  const cleanQuery = (query || "").trim().toLowerCase();
  const options = document.querySelectorAll(".service-search-option");
  let visibleCount = 0;
  options.forEach(opt => {
    const searchData = (opt.dataset.search || opt.dataset.title || "").toLowerCase();
    if (!cleanQuery || searchData.includes(cleanQuery)) {
      opt.style.display = "flex";
      visibleCount++;
    } else {
      opt.style.display = "none";
    }
  });
  const noResults = document.getElementById("noServicesFound");
  if (noResults) {
    noResults.style.display = visibleCount === 0 ? "block" : "none";
  }
};

window.selectServiceFromDropdown = function(serviceId) {
  const nativeSelect = document.getElementById("bookCategory") || document.getElementById("bookService");
  if (nativeSelect) {
    nativeSelect.value = serviceId;
    nativeSelect.dispatchEvent(new Event("change"));
  }
  const menu = document.getElementById("serviceDropdownMenu");
  if (menu) menu.style.display = "none";
};

// Close searchable service dropdown on outside click
document.addEventListener("click", (e) => {
  const group = document.getElementById("serviceSearchableGroup");
  const menu = document.getElementById("serviceDropdownMenu");
  if (menu && menu.style.display === "block") {
    if (group && !group.contains(e.target)) {
      menu.style.display = "none";
    }
  }
});

// Track multiple problem photo files
window.FIX_UPLOADED_PROBLEM_FILES = [];

function initPhotoUpload() {
  const uploadInput = document.getElementById("problemPhotoInput");
  const dropzone = document.getElementById("problemPhotoDropzone");
  const previewGrid = document.getElementById("photoPreviewContainer") || document.getElementById("photoPreviewBox");

  if (!uploadInput || !previewGrid) return;

  function renderPreviews() {
    if (!window.FIX_UPLOADED_PROBLEM_FILES || window.FIX_UPLOADED_PROBLEM_FILES.length === 0) {
      previewGrid.innerHTML = "";
      previewGrid.style.display = "none";
      return;
    }

    previewGrid.style.display = "grid";
    previewGrid.innerHTML = "";

    window.FIX_UPLOADED_PROBLEM_FILES.forEach((file, idx) => {
      const card = document.createElement("div");
      card.style.cssText = "position:relative; border-radius:8px; overflow:hidden; border:1px solid #cbd5e1; background:#f8fafc; aspect-ratio:1; box-shadow:0 1px 3px rgba(0,0,0,0.08);";

      const img = document.createElement("img");
      img.style.cssText = "width:100%; height:100%; object-fit:cover; display:block;";
      img.alt = file.name;

      const reader = new FileReader();
      reader.onload = (e) => {
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);

      const removeBtn = document.createElement("button");
      removeBtn.type = "button";
      removeBtn.innerHTML = "×";
      removeBtn.title = "Remove photo";
      removeBtn.style.cssText = "position:absolute; top:4px; right:4px; width:22px; height:22px; background:rgba(220,38,38,0.85); color:#fff; border:none; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:14px; font-weight:bold; line-height:1; transition:background 0.2s;";
      removeBtn.onmouseover = () => { removeBtn.style.background = "#dc2626"; };
      removeBtn.onmouseout = () => { removeBtn.style.background = "rgba(220,38,38,0.85)"; };
      removeBtn.onclick = (e) => {
        e.stopPropagation();
        removePhotoAtIndex(idx);
      };

      const nameBadge = document.createElement("div");
      nameBadge.style.cssText = "position:absolute; bottom:0; left:0; right:0; background:rgba(15,23,42,0.75); color:#fff; font-size:0.65rem; padding:2px 4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:center;";
      nameBadge.textContent = file.name;

      card.appendChild(img);
      card.appendChild(removeBtn);
      card.appendChild(nameBadge);
      previewGrid.appendChild(card);
    });
  }

  function handleFiles(files) {
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (!file.type.startsWith("image/")) {
        alert(`File "${file.name}" is not an image.`);
        continue;
      }
      if (file.size > 10 * 1024 * 1024) {
        alert(`File "${file.name}" exceeds 10MB limit.`);
        continue;
      }
      const exists = window.FIX_UPLOADED_PROBLEM_FILES.some(f => f.name === file.name && f.size === file.size && f.lastModified === file.lastModified);
      if (!exists) {
        window.FIX_UPLOADED_PROBLEM_FILES.push(file);
      }
    }
    renderPreviews();
  }

  function removePhotoAtIndex(idx) {
    window.FIX_UPLOADED_PROBLEM_FILES.splice(idx, 1);
    renderPreviews();
  }

  window.clearPhotoPreview = function() {
    window.FIX_UPLOADED_PROBLEM_FILES = [];
    if (uploadInput) uploadInput.value = "";
    if (previewGrid) {
      previewGrid.innerHTML = "";
      previewGrid.style.display = "none";
    }
  };

  uploadInput.addEventListener("change", (e) => {
    if (e.target.files && e.target.files.length > 0) {
      handleFiles(e.target.files);
    }
  });

  if (dropzone) {
    ['dragenter', 'dragover'].forEach(eventName => {
      dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.style.borderColor = "var(--primary)";
        dropzone.style.background = "rgba(14, 116, 144, 0.05)";
      }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropzone.addEventListener(eventName, (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropzone.style.borderColor = "";
        dropzone.style.background = "";
      }, false);
    });

    dropzone.addEventListener('drop', (e) => {
      const dt = e.dataTransfer;
      if (dt && dt.files && dt.files.length > 0) {
        handleFiles(dt.files);
      }
    });
  }
}

function clearPhotoPreview() {
  if (typeof window.clearPhotoPreview === "function") {
    window.clearPhotoPreview();
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
