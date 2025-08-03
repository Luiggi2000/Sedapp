document.addEventListener("alpine:init", () => {
  window.Alpine = window.Alpine || {}
  Alpine.data("evidenciasData", (evidencias, ordenes) => ({
    evidencias: evidencias,
    ordenes: ordenes,
    filteredEvidencias: evidencias,
    searchTerm: "",
    selectedTipo: "",
    selectedOrden: "",
    showModal: false,
    form: {
      orden_corte_id: "",
      tipo: "",
      imagen: null,
      observaciones: "",
    },

    init() {
      this.filterEvidencias()
    },

    filterEvidencias() {
      this.filteredEvidencias = this.evidencias.filter((evidencia) => {
        const matchesSearch =
          !this.searchTerm ||
          (evidencia.observaciones && evidencia.observaciones.toLowerCase().includes(this.searchTerm.toLowerCase()))

        const matchesTipo = !this.selectedTipo || evidencia.tipo === this.selectedTipo
        const matchesOrden = !this.selectedOrden || evidencia.orden_corte_id == this.selectedOrden

        return matchesSearch && matchesTipo && matchesOrden
      })
    },

    openCreateModal() {
      this.resetForm()
      this.showModal = true
    },

    closeModal() {
      this.showModal = false
      this.resetForm()
    },

    resetForm() {
      this.form = {
        orden_corte_id: "",
        tipo: "",
        imagen: null,
        observaciones: "",
      }
    },

    handleFileChange(event) {
      this.form.imagen = event.target.files[0]
    },

    async submitForm() {
      try {
        const formData = new FormData()
        formData.append("orden_corte_id", this.form.orden_corte_id)
        formData.append("tipo", this.form.tipo)
        formData.append("imagen", this.form.imagen)
        formData.append("observaciones", this.form.observaciones)
        formData.append("_token", document.querySelector('meta[name="csrf-token"]').content)

        const response = await fetch("/evidencias", {
          method: "POST",
          body: formData,
        })

        if (response.ok) {
          window.location.reload()
        } else {
          alert("Error al subir la evidencia")
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error al subir la evidencia")
      }
    },

    async confirmDelete(evidencia) {
      if (confirm("¿Estás seguro de que quieres eliminar esta evidencia?")) {
        try {
          const formData = new FormData()
          formData.append("_method", "DELETE")
          formData.append("_token", document.querySelector('meta[name="csrf-token"]').content)

          const response = await fetch(`/evidencias/${evidencia.id}`, {
            method: "POST",
            body: formData,
          })

          if (response.ok) {
            window.location.reload()
          } else {
            alert("Error al eliminar la evidencia")
          }
        } catch (error) {
          console.error("Error:", error)
          alert("Error al eliminar la evidencia")
        }
      }
    },
  }))
})
