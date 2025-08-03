document.addEventListener("alpine:init", () => {
  window.Alpine.data("rolesData", (roles) => ({
    roles: roles,
    filteredRoles: roles,
    searchTerm: "",
    showModal: false,
    editingRole: null,
    form: {
      name: "",
      guard_name: "web",
    },

    init() {
      this.filterRoles()
    },

    filterRoles() {
      this.filteredRoles = this.roles.filter((role) => {
        return (
          !this.searchTerm ||
          role.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
          role.guard_name.toLowerCase().includes(this.searchTerm.toLowerCase())
        )
      })
    },

    openCreateModal() {
      this.editingRole = null
      this.resetForm()
      this.showModal = true
    },

    openEditModal(role) {
      this.editingRole = role
      this.form = {
        name: role.name,
        guard_name: role.guard_name,
      }
      this.showModal = true
    },

    closeModal() {
      this.showModal = false
      this.editingRole = null
      this.resetForm()
    },

    resetForm() {
      this.form = {
        name: "",
        guard_name: "web",
      }
    },

    async submitForm() {
      try {
        const url = this.editingRole ? `/roles/${this.editingRole.id}` : "/roles"

        const formData = new FormData()
        Object.keys(this.form).forEach((key) => {
          formData.append(key, this.form[key])
        })

        if (this.editingRole) {
          formData.append("_method", "PUT")
        }
        formData.append("_token", document.querySelector('meta[name="csrf-token"]').content)

        const response = await fetch(url, {
          method: "POST",
          body: formData,
        })

        if (response.ok) {
          window.location.reload()
        } else {
          alert("Error al guardar el rol")
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error al guardar el rol")
      }
    },

    async confirmDelete(role) {
      if (confirm(`¿Estás seguro de que quieres eliminar el rol "${role.name}"?`)) {
        try {
          const formData = new FormData()
          formData.append("_method", "DELETE")
          formData.append("_token", document.querySelector('meta[name="csrf-token"]').content)

          const response = await fetch(`/roles/${role.id}`, {
            method: "POST",
            body: formData,
          })

          if (response.ok) {
            window.location.reload()
          } else {
            alert("Error al eliminar el rol")
          }
        } catch (error) {
          console.error("Error:", error)
          alert("Error al eliminar el rol")
        }
      }
    },
  }))
})
