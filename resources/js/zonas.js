// Zonas Alpine.js component
document.addEventListener("alpine:init", () => {
  window.Alpine = window.Alpine || {}
  const Alpine = window.Alpine

  Alpine.data("zonasData", () => ({
    zonas: window.zonasServerData || [],
    filteredZonas: [],
    searchTerm: "",
    showModal: false,
    showDeleteModal: false,
    editingZona: false,
    zoneToDelete: null,
    form: {
      nombre: "",
      descripcion: "",
    },
    selectedZona: null,

    init() {
      this.filterZonas()
    },

    filterZonas() {
      this.filteredZonas = this.zonas.filter((zona) => {
        const matchesSearch =
          zona.nombre.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
          (zona.descripcion && zona.descripcion.toLowerCase().includes(this.searchTerm.toLowerCase()))
        return matchesSearch
      })
    },

    openCreateModal() {
      this.form = { nombre: "", descripcion: "" }
      this.editingZona = false
      this.selectedZona = null
      this.showModal = true
      this.$nextTick(() => {
        document.querySelector('input[x-model="form.nombre"]')?.focus()
      })
    },

    openEditModal(zona) {
      this.form = { ...zona }
      this.editingZona = true
      this.selectedZona = zona
      this.showModal = true
      this.$nextTick(() => {
        document.querySelector('input[x-model="form.nombre"]')?.focus()
      })
    },

    closeModal() {
      this.showModal = false
      this.editingZona = false
      this.selectedZona = null
      this.form = { nombre: "", descripcion: "" }
    },

    async submitForm() {
      if (!this.form.nombre.trim()) {
        alert("El nombre de la zona es requerido.")
        return
      }

      try {
        const method = this.editingZona ? "PUT" : "POST"
        const url = this.editingZona ? `/zonas/${this.selectedZona.id}` : "/zonas"

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
          if (this.editingZona) {
            const index = this.zonas.findIndex((z) => z.id === this.selectedZona.id)
            if (index !== -1) {
              this.zonas[index] = { ...this.form, id: this.selectedZona.id, created_at: this.selectedZona.created_at }
            }
            window.addActivity(`editó zona ${this.form.nombre}`)
          } else {
            const newId = Math.max(...this.zonas.map((z) => z.id), 0) + 1
            const newZona = {
              ...this.form,
              id: newId,
              created_at: new Date().toISOString(),
              orden_cortes_count: 0,
            }
            this.zonas.push(newZona)
            window.addActivity(`creó zona ${this.form.nombre}`)
          }
          this.filterZonas()
          this.closeModal()
          alert(data.message || "Zona guardada exitosamente")
        } else {
          alert("Error al guardar zona: " + (data.message || "Error desconocido"))
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error de red o servidor.")
      }
    },

    confirmDelete(zona) {
      this.zoneToDelete = zona
      this.showDeleteModal = true
    },

    async deleteZone() {
      if (!this.zoneToDelete) return

      try {
        const response = await fetch(`/zonas/${this.zoneToDelete.id}`, {
          method: "DELETE",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
        })

        const data = await response.json()

        if (response.ok) {
          this.zonas = this.zonas.filter((zona) => zona.id !== this.zoneToDelete.id)
          window.addActivity(`eliminó zona ${this.zoneToDelete.nombre}`)
          this.filterZonas()
          this.showDeleteModal = false
          this.zoneToDelete = null
          alert(data.message || "Zona eliminada exitosamente")
        } else {
          alert("Error al eliminar zona: " + (data.message || "Error desconocido"))
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error de red o servidor.")
      }
    },
  }))
})
