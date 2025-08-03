// Órdenes Alpine.js component
document.addEventListener("alpine:init", () => {
  window.Alpine.data("ordenesData", () => ({
    ordenes: window.ordenesServerData || [],
    zonas: window.zonasServerData || [],
    tecnicos: window.tecnicosServerData || [],
    afectados: window.afectadosServerData || [],
    filteredOrdenes: [],
    searchTerm: "",
    selectedEstado: "",
    selectedZona: "",
    selectedTecnico: "",
    showModal: false,
    showDeleteModal: false,
    editingOrden: false,
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
    selectedOrden: null,

    init() {
      this.filterOrdenes()
    },

    filterOrdenes() {
      this.filteredOrdenes = this.ordenes.filter((orden) => {
        const matchesSearch =
          orden.direccion.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
          (orden.zona?.nombre || "").toLowerCase().includes(this.searchTerm.toLowerCase()) ||
          (orden.tecnico?.name || "").toLowerCase().includes(this.searchTerm.toLowerCase())

        const matchesEstado = this.selectedEstado === "" || orden.estado === this.selectedEstado
        const matchesZona = this.selectedZona === "" || orden.zona_id == this.selectedZona
        const matchesTecnico = this.selectedTecnico === "" || orden.tecnico_id == this.selectedTecnico

        return matchesSearch && matchesEstado && matchesZona && matchesTecnico
      })
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

    hasActiveFilters() {
      return this.searchTerm || this.selectedEstado || this.selectedZona || this.selectedTecnico
    },

    clearFilters() {
      this.searchTerm = ""
      this.selectedEstado = ""
      this.selectedZona = ""
      this.selectedTecnico = ""
      this.filterOrdenes()
    },

    openCreateModal() {
      this.form = {
        zona_id: "",
        tecnico_id: "",
        afectado_id: "",
        fecha: new Date().toISOString().split("T")[0],
        direccion: "",
        estado: "pendiente",
        observaciones: "",
      }
      this.editingOrden = false
      this.selectedOrden = null
      this.showModal = true
    },

    openEditModal(orden) {
      this.form = { ...orden }
      this.editingOrden = true
      this.selectedOrden = orden
      this.showModal = true
    },

    closeModal() {
      this.showModal = false
      this.editingOrden = false
      this.selectedOrden = null
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

    async submitForm() {
      if (!this.isFormValid()) {
        alert("Por favor, complete todos los campos requeridos.")
        return
      }

      try {
        const method = this.editingOrden ? "PUT" : "POST"
        const url = this.editingOrden ? `/ordenes/${this.selectedOrden.id}` : "/ordenes"

        const response = await fetch(url, {
          method: method,
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
          body: JSON.stringify(this.form),
        })

        const data = await response.json()

        if (response.ok) {
          if (this.editingOrden) {
            const index = this.ordenes.findIndex((o) => o.id === this.selectedOrden.id)
            if (index !== -1) {
              this.ordenes[index] = {
                ...this.form,
                id: this.selectedOrden.id,
                zona: this.zonas.find((z) => z.id == this.form.zona_id),
                tecnico: this.tecnicos.find((t) => t.id == this.form.tecnico_id),
                afectado: this.afectados.find((a) => a.id == this.form.afectado_id),
              }
            }
            window.addActivity(`editó orden #${this.selectedOrden.id}`)
          } else {
            const newId = Math.max(...this.ordenes.map((o) => o.id), 0) + 1
            const newOrden = {
              ...this.form,
              id: newId,
              zona: this.zonas.find((z) => z.id == this.form.zona_id),
              tecnico: this.tecnicos.find((t) => t.id == this.form.tecnico_id),
              afectado: this.afectados.find((a) => a.id == this.form.afectado_id),
            }
            this.ordenes.push(newOrden)
            window.addActivity(`creó orden #${newId}`)
          }
          this.filterOrdenes()
          this.closeModal()
          alert(data.message || "Orden guardada exitosamente")
        } else {
          alert("Error al guardar orden: " + (data.message || "Error desconocido"))
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error de red o servidor.")
      }
    },

    confirmDelete(orden) {
      this.orderToDelete = orden
      this.showDeleteModal = true
    },

    async deleteOrder() {
      if (!this.orderToDelete) return

      try {
        const response = await fetch(`/ordenes/${this.orderToDelete.id}`, {
          method: "DELETE",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
        })

        const data = await response.json()

        if (response.ok) {
          this.ordenes = this.ordenes.filter((orden) => orden.id !== this.orderToDelete.id)
          window.addActivity(`eliminó orden #${this.orderToDelete.id}`)
          this.filterOrdenes()
          this.showDeleteModal = false
          this.orderToDelete = null
          alert(data.message || "Orden eliminada exitosamente")
        } else {
          alert("Error al eliminar orden: " + (data.message || "Error desconocido"))
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error de red o servidor.")
      }
    },
  }))
})
