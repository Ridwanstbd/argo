async function handleAPIError(response) {
  const contentType = response.headers.get("content-type");
  if (contentType && contentType.includes("application/json")) {
    const error = await response.json();
    throw new Error(error.error || "Unknown error occurred");
  } else {
    const text = await response.text();
    console.error("Non-JSON response:", text);
    throw new Error("Invalid server response");
  }
}

async function openEditModal(id, status, waktu) {
  const modal = document.querySelector("#editPesanan");

  document.getElementById("edit_idpesanan").value = id;
  document.getElementById("edit_status").value = status;
  const formattedWaktu = waktu.substring(0, 16);
  document.getElementById("edit_waktu").value = formattedWaktu;

  // Fetch order details
  try {
    const formData = new FormData();
    formData.append("id_pesanan", id);
    formData.append(
      "csrf_token",
      document.querySelector('input[name="csrf_token"]').value
    );

    const response = await fetch("get_order_details.php", {
      // Updated URL
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      await handleAPIError(response);
    }

    const data = await response.json();

    // Update order details in modal
    const detailsContainer = document.getElementById("order_details");
    detailsContainer.innerHTML = `
      <div class="customer-details">
        <h5 class="detail-section-title">Data Pelanggan</h5>
        <table class="detail-table">
          <tr>
            <td><strong>Nama</strong></td>
            <td>: ${data.order.nama}</td>
          </tr>
          <tr>
            <td><strong>No HP</strong></td>
            <td>: ${data.order.no_hp}</td>
          </tr>
          <tr>
            <td><strong>Alamat</strong></td>
            <td>: ${data.order.alamat}</td>
          </tr>
        </table>
      </div>
      
      <div class="order-items">
        <h5 class="detail-section-title">Detail Pesanan</h5>
        <div class="items-table">
          <table class="detail-table">
            <thead>
              <tr>
                <th>Item</th>
                <th>Layanan</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              ${data.items
                .map(
                  (item) => `
                <tr>
                  <td>${item.nama_barang}</td>
                  <td>${item.layanan}</td>
                  <td>${item.jumlah}</td>
                  <td>Rp ${formatNumber(item.harga)}</td>
                  <td>Rp ${formatNumber(item.subtotal)}</td>
                </tr>
              `
                )
                .join("")}
            </tbody>
          </table>
        </div>
        <div class="total">
          <strong>Total:</strong> Rp ${formatNumber(
            data.items.reduce((sum, item) => sum + parseFloat(item.subtotal), 0)
          )}
        </div>
      </div>
    `;
  } catch (error) {
    console.error("Error fetching order details:", error);
    alert("Gagal mengambil detail pesanan");
  }

  window.modalSystem.openModal(modal);
}

function formatNumber(num) {
  return new Intl.NumberFormat("id-ID").format(num);
}

function confirmDelete(id) {
  if (confirm("Apakah Anda yakin ingin menghapus pesanan ini?")) {
    const form = document.createElement("form");
    form.method = "POST";
    form.style.display = "none";

    const idInput = document.createElement("input");
    idInput.type = "hidden";
    idInput.name = "idpesanan";
    idInput.value = id;

    const submitInput = document.createElement("input");
    submitInput.type = "hidden";
    submitInput.name = "hapuspesanan";
    submitInput.value = "1";

    const csrfInput = document.createElement("input");
    csrfInput.type = "hidden";
    csrfInput.name = "csrf_token";
    csrfInput.value = document.querySelector('input[name="csrf_token"]').value;

    form.appendChild(idInput);
    form.appendChild(submitInput);
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    form.submit();
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const forms = document.querySelectorAll("form");
  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      const requiredFields = form.querySelectorAll("[required]");
      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          event.preventDefault();
          alert("Mohon isi semua field yang wajib diisi");
        }
      });

      const waktuField = form.querySelector('input[type="datetime-local"]');
      if (waktuField && waktuField.value) {
        const selectedTime = new Date(waktuField.value);
        const now = new Date();
        if (selectedTime < now) {
          event.preventDefault();
          alert("Waktu pesanan tidak boleh di masa lalu");
        }
      }
    });
  });
});
