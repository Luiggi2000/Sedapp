document.addEventListener("alpine:init", () => {
  window.Alpine.data("ordenesData", () => ({
    ordenes: [],
    zonas: [],
    tecnicos: [],
    afectados: [],
    filteredOrdenes: [],
    searchTerm: "",
    selectedEstado: "",
    selectedZona: "",
    selectedTecnico: "",
    showModal: false,
    showDeleteModal: false,
    editingOrden: null,
    orderToDelete: null,
    form: {
      zona_id: "",
      tecnico_id: "",
      afectado_id: "",
      fecha: "",
      direccion: "",
      estado: "pendiente",
      observaciones: "",
    },

    init() {
      this.ordenes = window.ordenesServerData || []
      this.zonas = window.zonasServerData || []
      this.tecnicos = window.tecnicosServerData || []
      this.afectados = window.afectadosServerData || []
      this.filteredOrdenes = [...this.ordenes]
      this.filterOrdenes()
      console.log("Ordenes component initialized with", this.ordenes.length, "orders")
    },

    filterOrdenes() {
      let filtered = [...this.ordenes]

      // Search filter
      if (this.searchTerm.trim()) {
        const searchLower = this.searchTerm.toLowerCase().trim()
        filtered = filtered.filter((orden) => {
          return (
            orden.direccion.toLowerCase().includes(searchLower) ||
            (orden.zona?.nombre && orden.zona.nombre.toLowerCase().includes(searchLower)) ||
            (orden.tecnico?.name && orden.tecnico.name.toLowerCase().includes(searchLower)) ||
            (orden.afectado?.name && orden.afectado.name.toLowerCase().includes(searchLower))
          )
        })
      }

      // Estado filter
      if (this.selectedEstado) {
        filtered = filtered.filter((orden) => orden.estado === this.selectedEstado)
      }

      // Zona filter
      if (this.selectedZona) {
        filtered = filtered.filter((orden) => orden.zona_id == this.selectedZona)
      }

      // Tecnico filter
      if (this.selectedTecnico) {
        filtered = filtered.filter((orden) => orden.tecnico_id == this.selectedTecnico)
      }

      this.filteredOrdenes = filtered
    },

    clearFilters() {
      this.searchTerm = ""
      this.selectedEstado = ""
      this.selectedZona = ""
      this.selectedTecnico = ""
      this.filterOrdenes()
      console.log("Filters cleared")
    },

    hasActiveFilters() {
      return this.searchTerm || this.selectedEstado || this.selectedZona || this.selectedTecnico
    },

    getOrdersByStatus(status) {
      return this.ordenes.filter((orden) => orden.estado === status)
    },

    getEstadoClass(estado) {
      const classes = {
        pendiente: "bg-yellow-100 text-yellow-800",
        en_proceso: "bg-blue-100 text-blue-800",
        completada: "bg-green-100 text-green-800",
        cancelada: "bg-red-100 text-red-800",
      }
      return classes[estado] || "bg-gray-100 text-gray-800"
    },

    getEstadoText(estado) {
      const texts = {
        pendiente: "Pendiente",
        en_proceso: "En Proceso",
        completada: "Completada",
        cancelada: "Cancelada",
      }
      return texts[estado] || estado
    },

    isFormValid() {
      return (
        this.form.zona_id &&
        this.form.tecnico_id &&
        this.form.afectado_id &&
        this.form.fecha &&
        this.form.direccion.trim() &&
        this.form.estado
      )
    },

    openCreateModal() {
      this.editingOrden = null
      this.resetForm()
      this.showModal = true
      // Set default date to today
      this.form.fecha = new Date().toISOString().split("T")[0]
      console.log("Opening create order modal")

      // Focus on first input after modal opens
      this.$nextTick(() => {
        const select = document.querySelector('select[x-model="form.zona_id"]')
        if (select) select.focus()
      })
    },

    openEditModal(orden) {
      this.editingOrden = orden
      this.form = {
        zona_id: orden.zona_id || "",
        tecnico_id: orden.tecnico_id || "",
        afectado_id: orden.afectado_id || "",
        fecha: orden.fecha || "",
        direccion: orden.direccion || "",
        estado: orden.estado || "pendiente",
        observaciones: orden.observaciones || "",
      }
      this.showModal = true
      console.log("Opening edit modal for order:", orden.id)

      // Focus on first input after modal opens
      this.$nextTick(() => {
        const select = document.querySelector('select[x-model="form.zona_id"]')
        if (select) select.focus()
      })
    },

    closeModal() {
      this.showModal = false
      this.editingOrden = null
      this.resetForm()
      console.log("Order modal closed")
    },

    resetForm() {
      this.form = {
        zona_id: "",
        tecnico_id: "",
        afectado_id: "",
        fecha: "",
        direccion: "",
        estado: "pendiente",
        observaciones: "",
      }
    },

    async submitForm() {
      console.log("Submitting order form:", this.form)

      // Validate form
      if (!this.isFormValid()) {
        alert("Por favor, completa todos los campos obligatorios.")
        return
      }

      if (this.form.direccion.length > 500) {
        alert("La dirección no puede exceder 500 caracteres.")
        return
      }

      if (this.form.observaciones && this.form.observaciones.length > 1000) {
        alert("Las observaciones no pueden exceder 1000 caracteres.")
        return
      }

      try {
        const url = this.editingOrden ? `/ordenes/${this.editingOrden.id}` : "/ordenes"

        const formData = new FormData()
        Object.keys(this.form).forEach((key) => {
          formData.append(key, this.form[key] || "")
        })

        if (this.editingOrden) {
          formData.append("_method", "PUT")
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')
        if (csrfToken) {
          formData.append("_token", csrfToken.content)
        }

        const response = await fetch(url, {
          method: "POST",
          body: formData,
        })

        if (response.ok) {
          // Add activity tracking
          if (window.addActivity) {
            const action = this.editingOrden
              ? `Actualizó la orden #${String(this.editingOrden.id).padStart(4, "0")}`
              : `Creó una nueva orden de corte`
            window.addActivity(action)
          }

          // Show success message and reload
          alert(this.editingOrden ? "Orden actualizada exitosamente." : "Orden creada exitosamente.")
          window.location.reload()
        } else {
          // Try to parse error message
          const data = await response.text()
          try {
            const errorData = JSON.parse(data)
            if (errorData.errors) {
              const errorMessages = Object.values(errorData.errors).flat()
              alert("Errores de validación:\n" + errorMessages.join("\n"))
            } else {
              alert(errorData.message || "Error al guardar la orden")
            }
          } catch (e) {
            alert("Error al guardar la orden. Por favor, intenta nuevamente.")
          }
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error de conexión. Por favor, verifica tu conexión a internet.")
      }
    },

    confirmDelete(orden) {
      this.orderToDelete = orden
      this.showDeleteModal = true
      console.log("Confirming delete for order:", orden.id)
    },

    async deleteOrder() {
      if (!this.orderToDelete) return

      console.log("Deleting order:", this.orderToDelete.id)

      try {
        const formData = new FormData()
        formData.append("_method", "DELETE")

        const csrfToken = document.querySelector('meta[name="csrf-token"]')
        if (csrfToken) {
          formData.append("_token", csrfToken.content)
        }

        const response = await fetch(`/ordenes/${this.orderToDelete.id}`, {
          method: "POST",
          body: formData,
        })

        if (response.ok) {
          // Add activity tracking
          if (window.addActivity) {
            window.addActivity(`Eliminó la orden #${String(this.orderToDelete.id).padStart(4, "0")}`)
          }

          // Show success message and reload
          alert("Orden eliminada exitosamente.")
          window.location.reload()
        } else {
          // Try to parse error message
          const data = await response.text()
          try {
            const errorData = JSON.parse(data)
            alert(errorData.message || "Error al eliminar la orden")
          } catch (e) {
            if (response.status === 422) {
              alert("No se puede eliminar la orden porque tiene evidencias asociadas.")
            } else {
              alert("Error al eliminar la orden. Por favor, intenta nuevamente.")
            }
          }
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error de conexión. Por favor, verifica tu conexión a internet.")
      } finally {
        this.showDeleteModal = false
        this.orderToDelete = null
      }
    },
  }))
})

// Helper functions for orders
window.orderUtils = {
  formatOrderId: (id) => {
    return "#" + String(id).padStart(4, "0")
  },

  validateOrderForm: (form) => {
    const errors = []

    if (!form.zona_id) errors.push("Debe seleccionar una zona")
    if (!form.tecnico_id) errors.push("Debe seleccionar un técnico")
    if (!form.afectado_id) errors.push("Debe seleccionar un afectado")
    if (!form.fecha) errors.push("Debe seleccionar una fecha")
    if (!form.direccion.trim()) errors.push("Debe ingresar una dirección")
    if (!form.estado) errors.push("Debe seleccionar un estado")

    return errors
  },
}

console.log("Ordenes JavaScript loaded")
