document.addEventListener("alpine:init", () => {
  // This component is initialized in the Blade template
  // All data comes from Laravel controllers, no API calls needed
  console.log("Users management initialized")

  window.Alpine.data("usuariosData", (usuarios, roles) => ({
    usuarios: usuarios,
    roles: roles,
    filteredUsers: usuarios,
    searchTerm: "",
    selectedRole: "",
    showModal: false,
    editingUser: null,
    form: {
      name: "",
      apellido: "",
      email: "",
      telefono: "",
      rol_id: "",
      password: "",
      password_confirmation: "",
    },

    init() {
      this.filterUsers()
    },

    filterUsers() {
      this.filteredUsers = window.filterUsers(this.usuarios, this.searchTerm, this.selectedRole)
    },

    openCreateModal() {
      this.editingUser = null
      this.resetForm()
      this.showModal = true
    },

    openEditModal(user) {
      this.editingUser = user
      this.form = {
        name: user.name,
        apellido: user.apellido,
        email: user.email,
        telefono: user.telefono || "",
        rol_id: user.rol_id,
        password: "",
        password_confirmation: "",
      }
      this.showModal = true
    },

    closeModal() {
      this.showModal = false
      this.editingUser = null
      this.resetForm()
    },

    resetForm() {
      this.form = {
        name: "",
        apellido: "",
        email: "",
        telefono: "",
        rol_id: "",
        password: "",
        password_confirmation: "",
      }
    },

    async submitForm() {
      const errors = window.validateUserForm(this.form)
      if (errors.length > 0) {
        alert(errors.join("\n"))
        return
      }

      try {
        const url = this.editingUser ? `/usuarios/${this.editingUser.id}` : "/usuarios"
        const method = this.editingUser ? "PUT" : "POST"

        const formData = new FormData()
        Object.keys(this.form).forEach((key) => {
          if (this.form[key]) {
            formData.append(key, this.form[key])
          }
        })

        if (this.editingUser) {
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
          alert("Error al guardar el usuario")
        }
      } catch (error) {
        console.error("Error:", error)
        alert("Error al guardar el usuario")
      }
    },

    async confirmDelete(user) {
      if (confirm(`¿Estás seguro de que quieres eliminar a ${user.name} ${user.apellido}?`)) {
        try {
          const formData = new FormData()
          formData.append("_method", "DELETE")
          formData.append("_token", document.querySelector('meta[name="csrf-token"]').content)

          const response = await fetch(`/usuarios/${user.id}`, {
            method: "POST",
            body: formData,
          })

          if (response.ok) {
            window.location.reload()
          } else {
            alert("Error al eliminar el usuario")
          }
        } catch (error) {
          console.error("Error:", error)
          alert("Error al eliminar el usuario")
        }
      }
    },
  }))
})

// Helper functions for user management
window.formatUserRole = (role) => {
  if (!role) return "Sin rol"
  return role.name || "Rol desconocido"
}

window.formatUserStatus = (user) => (user.email_verified_at ? "Activo" : "Inactivo")

window.getUserInitials = (user) => {
  if (!user.name || !user.apellido) return "??"
  return (user.name.charAt(0) + user.apellido.charAt(0)).toUpperCase()
}

// Export functionality
window.exportUsersToCSV = (users) => {
  const headers = ["Nombre", "Apellido", "Email", "Teléfono", "Rol", "Estado"]
  const rows = users.map((user) => [
    `"${user.name}"`,
    `"${user.apellido}"`,
    `"${user.email}"`,
    `"${user.telefono || "N/A"}"`,
    `"${user.role?.name || "Sin rol"}"`,
    `"${user.email_verified_at ? "Activo" : "Inactivo"}"`,
  ])

  const csvContent = "data:text/csv;charset=utf-8," + headers.join(",") + "\n" + rows.map((e) => e.join(",")).join("\n")

  const encodedUri = encodeURI(csvContent)
  const link = document.createElement("a")
  link.setAttribute("href", encodedUri)
  link.setAttribute("download", `usuarios_${new Date().toISOString().split("T")[0]}.csv`)
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)

  if (window.addActivity) {
    window.addActivity("exportó lista de usuarios")
  }
}

// Form validation helpers
window.validateUserForm = (user) => {
  const errors = []

  if (!user.name || user.name.trim().length < 2) {
    errors.push("El nombre debe tener al menos 2 caracteres")
  }

  if (!user.apellido || user.apellido.trim().length < 2) {
    errors.push("El apellido debe tener al menos 2 caracteres")
  }

  if (!user.email || !isValidEmail(user.email)) {
    errors.push("Debe proporcionar un email válido")
  }

  if (!user.rol_id) {
    errors.push("Debe seleccionar un rol")
  }

  return errors
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

// Password validation
window.validatePassword = (password, confirmation) => {
  const errors = []

  if (!password || password.length < 8) {
    errors.push("La contraseña debe tener al menos 8 caracteres")
  }

  if (password !== confirmation) {
    errors.push("Las contraseñas no coinciden")
  }

  return errors
}

// Search and filter helpers
window.filterUsers = (users, searchTerm, roleFilter) =>
  users.filter((user) => {
    const matchesSearch =
      !searchTerm ||
      user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      user.apellido.toLowerCase().includes(searchTerm.toLowerCase()) ||
      user.email.toLowerCase().includes(searchTerm.toLowerCase())

    const matchesRole = !roleFilter || user.rol_id == roleFilter

    return matchesSearch && matchesRole
  })

// Pagination helpers
window.paginateArray = (array, page, itemsPerPage) => {
  const startIndex = (page - 1) * itemsPerPage
  const endIndex = startIndex + itemsPerPage
  return array.slice(startIndex, endIndex)
}

window.getTotalPages = (totalItems, itemsPerPage) => Math.ceil(totalItems / itemsPerPage)

window.getPaginationInfo = (currentPage, totalItems, itemsPerPage) => {
  const startEntry = totalItems > 0 ? (currentPage - 1) * itemsPerPage + 1 : 0
  const endEntry = Math.min(currentPage * itemsPerPage, totalItems)

  return { startEntry, endEntry }
}
