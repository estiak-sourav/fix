/**
 * FIX - Building and Facility Maintenance Services
 * Vendor Partnership Registration Flow
 */

document.addEventListener("DOMContentLoaded", () => {
  initVendorForm();
  initServiceCheckboxesCounter();
});

function initServiceCheckboxesCounter() {
  const checkboxes = document.querySelectorAll('input[name="vendorServices"]');
  const counterBadge = document.getElementById("selectedServicesCount");

  function updateCount() {
    const checked = Array.from(checkboxes).filter(c => c.checked);
    if (counterBadge) {
      counterBadge.textContent = `${checked.length} Selected`;
      if (checked.length > 0) {
        counterBadge.className = "badge badge-accent";
      } else {
        counterBadge.className = "badge badge-primary";
      }
    }
  }

  checkboxes.forEach(cb => {
    cb.addEventListener("change", updateCount);
  });
  updateCount();
}

function initVendorForm() {
  const form = document.getElementById("vendorApplicationForm");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const vendorName = document.getElementById("vendorCompanyName").value.trim();
    const contactPerson = document.getElementById("vendorContactPerson").value.trim();
    const phone = document.getElementById("vendorPhone").value.trim();
    const email = document.getElementById("vendorEmail").value.trim();
    const address = document.getElementById("vendorAddress").value.trim();
    const city = document.getElementById("vendorCity").value.trim();
    
    // Checked services
    const checkedBoxes = Array.from(document.querySelectorAll('input[name="vendorServices"]:checked'));
    const selectedServices = checkedBoxes.map(cb => cb.value);

    const experience = document.getElementById("vendorExperience").value;
    const technicians = document.getElementById("vendorTechnicians").value;
    const serviceArea = document.getElementById("vendorServiceArea").value;
    const tradeLicense = document.getElementById("vendorTradeLicense").value.trim();
    const companyDesc = document.getElementById("vendorCompanyDesc").value.trim();

    // Validation
    if (!vendorName || !contactPerson || !phone || !address || !city) {
      alert("Please complete all required business information fields marked with *");
      return;
    }

    if (selectedServices.length === 0) {
      alert("Please select at least ONE service category that your company provides.");
      return;
    }

    // BD Phone validation
    const cleanPhone = phone.replace(/\s+/g, '');
    if (!/(^(\+8801|8801|01))[3-9]{1}[0-9]{8}$/.test(cleanPhone)) {
      alert("Please enter a valid Bangladeshi contact phone number (e.g. 01712345678)");
      return;
    }

    // Button loading animation
    const submitBtn = form.querySelector("button[type='submit']");
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
      <svg class="spin" style="width:20px;height:20px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <circle cx="12" cy="12" r="10" stroke-width="4" stroke="currentColor" stroke-dasharray="32" stroke-linecap="round"></circle>
      </svg>
      Submitting Application for Review...
    `;

    setTimeout(() => {
      // Generate Vendor App ID
      const randomId = Math.floor(1000 + Math.random() * 9000);
      const appId = `VN-${randomId}`;

      const vendorRecord = {
        appId,
        timestamp: new Date().toISOString(),
        vendorName,
        contactPerson,
        phone,
        email,
        address,
        city,
        services: selectedServices,
        experience,
        technicians,
        serviceArea,
        tradeLicense,
        companyDesc,
        status: "Management Review Pending"
      };

      // Store in localStorage for prototype demonstration
      const existing = JSON.parse(localStorage.getItem("fix_vendors") || "[]");
      existing.push(vendorRecord);
      localStorage.setItem("fix_vendors", JSON.stringify(existing));

      // Reset button & form
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
      form.reset();
      initServiceCheckboxesCounter();

      // Show confirmation modal
      showVendorSuccessModal(vendorRecord);
    }, 1400);
  });
}

function showVendorSuccessModal(record) {
  const modal = document.getElementById("vendorSuccessModal");
  if (!modal) return;

  const appIdSpan = modal.querySelector(".modal-app-id");
  const compNameSpan = modal.querySelector(".modal-company-name");
  const servicesSpan = modal.querySelector(".modal-service-count");

  if (appIdSpan) appIdSpan.textContent = record.appId;
  if (compNameSpan) compNameSpan.textContent = record.vendorName;
  if (servicesSpan) servicesSpan.textContent = `${record.services.length} Service Categories (${record.services.slice(0, 3).join(', ')}${record.services.length > 3 ? '...' : ''})`;

  modal.classList.add("active");

  const closeBtn = modal.querySelector(".modal-close-btn");
  closeBtn?.addEventListener("click", () => {
    modal.classList.remove("active");
  });
}
