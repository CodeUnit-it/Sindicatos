document.addEventListener("DOMContentLoaded", () => {
  const API_BASE_URL = "http://localhost:8000";

  // --- 1. MENÚ & ANIMACIONES (Sin cambios) ---
  const menuToggle = document.querySelector(".menu-toggle");
  const mainNav = document.querySelector(".main-nav");
  if (menuToggle) {
    menuToggle.addEventListener("click", () =>
      mainNav.classList.toggle("show"),
    );
    document.querySelectorAll(".main-nav a").forEach((link) => {
      link.addEventListener("click", () => mainNav.classList.remove("show"));
    });
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("show-element");
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 },
  );

  document
    .querySelectorAll(".hidden-left, .hidden-right")
    .forEach((el) => observer.observe(el));

  const apiFetch = async (endpoint) => {
    try {
      const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        method: "GET",
        headers: {
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      });

      if (!response.ok) {
        // Si el servidor responde 404 o 500, lanzamos error
        throw new Error(`Error ${response.status}: Servidor no disponible`);
      }

      const contentType = response.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        // Si no es JSON, capturamos qué es para debugear
        const textoError = await response.text();
        console.error(
          "El servidor respondió con HTML en lugar de JSON. Verifica las rutas de Laravel.",
        );
        throw new TypeError("La respuesta no es un JSON válido.");
      }

      return await response.json();
    } catch (error) {
      throw error;
    }
  };

  // --- 2. CARGAR NOTICIAS ---
  const loadNews = async () => {
    const container = document.getElementById("news-container");
    if (!container) return;
    try {
      const data = await apiFetch("/api/news");
      if (data.length === 0) {
        container.innerHTML =
          '<p style="grid-column: 1/-1; text-align: center;">No hay noticias.</p>';
        return;
      }
      container.innerHTML = "";
      data.forEach((n) => {
        const imgPath = n.image
          ? `${API_BASE_URL}/storage/${n.image}`
          : "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=800";
        container.innerHTML += `
          <div class="news-item">
            <img src="${imgPath}" alt="${n.title}">
            <h3>${n.title}</h3>
            <div class="content">${n.content.substring(0, 150)}...</div>
            <div style="padding: 0 20px 20px; margin-top: auto;">
              <a href="noticia.html?id=${n.id}" class="btn">Leer más</a>
            </div>
          </div>`;
      });
    } catch (e) {
      console.error("Error news:", e);
    }
  };

  // --- 3. CARGAR DOCUMENTOS ---
  const loadDocuments = async () => {
    const convenioList = document.getElementById("lista-convenios");
    const quickAccess = document.querySelector(".quick-access .grid-cards");
    try {
      const data = await apiFetch("/api/documents");
      if (convenioList && data.some((d) => d.type === "convenio"))
        convenioList.innerHTML = "";

      data.forEach((doc) => {
        const rawPath = Array.isArray(doc.file_path)
          ? doc.file_path[0]
          : doc.file_path;
        const fileUrl = `${API_BASE_URL}/storage/${rawPath}`;

        if (doc.type === "convenio" && convenioList) {
          convenioList.innerHTML += `<li><a href="${fileUrl}" target="_blank"><i class="fas fa-file-pdf"></i> ${doc.title}</a></li>`;
        }
        if (doc.type === "formulario" && quickAccess) {
          quickAccess.insertAdjacentHTML(
            "afterbegin",
            `
            <a href="${fileUrl}" target="_blank" class="card-link">
              <div class="card show-element">
                <i class="fas fa-file-download icon"></i>
                <h3>${doc.title}</h3>
                <p>Descargar formulario oficial.</p>
              </div>
            </a>`,
          );
        }
      });
    } catch (e) {
      console.error("Error docs:", e);
    }
  };

  // --- 4. CARGAR SUELDOS ---
  const loadSalaries = async () => {
    const tableBody = document.getElementById("tabla-salarios-body");
    const updateLabel = document.querySelector(".update");

    try {
      const data = await apiFetch("/api/salaries");

      if (data && data.length > 0) {
        // 1. LIMPIAR la tabla
        tableBody.innerHTML = "";

        // 2. INSERTAR las filas reales
        data.forEach((s) => {
          tableBody.innerHTML += `
          <tr>
            <td>${s.category}</td>
            <td>$ ${Number(s.basic_salary).toLocaleString("es-AR")}</td>
            <td>$ ${Number(s.non_remunerative).toLocaleString("es-AR")}</td>
            <td>${new Date(s.effective_date).toLocaleDateString("es-AR")}</td>
          </tr>`;
        });

        // 3. ACTUALIZAR la fecha de actualización
        if (updateLabel && data[0].updated_at) {
          const ultima = new Date(data[0].updated_at);
          updateLabel.innerText = `Última actualización: ${ultima.toLocaleDateString("es-AR", { month: "long", year: "numeric" })}`;
        }
      }
    } catch (e) {
      console.error("Error al cargar salarios:", e);
    }
  };
  // --- 5. ENVIAR PRE-AFILIACIÓN CON SWEETALERT ---
  const formAfi = document.getElementById("form-pre-afiliacion");
  if (formAfi) {
    formAfi.addEventListener("submit", async (e) => {
      e.preventDefault();

      const btn = formAfi.querySelector("button");
      const originalText = btn.innerHTML; // Usamos innerHTML por si tiene iconos

      // Feedback visual: Spinner y desactivar botón
      btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Enviando...`;
      btn.disabled = true;

      const datos = {
        nombre: document.getElementById("nombre_afi").value,
        dni: document.getElementById("dni_afi").value,
        empresa: document.getElementById("empresa_afi").value,
        telefono: document.getElementById("tel_afi").value,
        email: document.getElementById("email_afi").value,
      };

      try {
        const response = await fetch(`${API_BASE_URL}/api/leads`, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: JSON.stringify(datos),
        });

        if (response.ok) {
          formAfi.reset();

          Swal.fire({
            title: "¡Solicitud enviada!",
            text: "Tus datos se registraron con éxito. Nos pondremos en contacto con vos pronto.",
            icon: "success",
            confirmButtonColor: "#19ca28",
            confirmButtonText: "Entendido",
          });
        } else {
          const errorData = await response.json();

          Swal.fire({
            title: "No se pudo enviar",
            text: errorData.message || "Verificá los datos e intentá de nuevo.",
            icon: "warning",
            confirmButtonColor: "#333",
          });
        }
      } catch (error) {
        console.error("Error leads:", error);

        Swal.fire({
          title: "Error de conexión",
          text: "Hubo un problema al conectar con el servidor. Intentá más tarde.",
          icon: "error",
          confirmButtonColor: "#d33",
        });
      } finally {
      // Restaurar el botón a su estado original
        btn.innerHTML = originalText;
        btn.disabled = false;
      }
    });
  }
  loadNews();
  loadDocuments();
  loadSalaries();
});
