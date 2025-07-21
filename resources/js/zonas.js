// This file is for zones-specific JavaScript, using Alpine.js for interactivity.
\
document.addEventListener('alpine:init\', () => \{
    Alpine.data('zoneManagement', () => (\
{
  // Initial data passed from Laravel Blade
  // Make sure these match the variable names in your Blade view\
  allZonas: @json($zones)
  ,\
        totalZonas:
  @json($totalZonas)
  ,\
        totalOrdenes:
  @json($totalOrdenes)
  ,\
        promedioOrdenes:
  @json($promedioOrdenes)
  ,

        searchTerm: '',
        filteredZonas: [],
        currentPage: 1,
        itemsPerPage: 5,
        isCreateModalOpen: false,
        isEditModalOpen: false,
        isDeleteModalOpen: false,\
        newZona: \
  {
    nombre:
    '\', sector: \'\' \},\
        editZona: \{ id: null, nombre: \'\', sector: \'\' \},
        deleteZona: null,

        init() \
    this.filterZonas()
    window.addActivity("accedió a la gestión de zonas")
    \
        \
    ,\

        filterZonas() \
    \
            this.filteredZonas = this.allZonas.filter(zona => \
    {
      \
      const lowerSearchTerm = this.searchTerm.toLowerCase()
      return zona.nombre.toLowerCase().includes(lowerSearchTerm) ||
                       zona.sector.toLowerCase().includes(lowerSearchTerm);
      \
            \
    }
    )
      this.currentPage = 1 // Reset to first page on filter\
      \
    ,\

        get totalPages() \
    return Math.ceil(this.filteredZonas.length / this.itemsPerPage);
    \
        \
    ,\

        get currentZonas() \
    {
      const start = (this.currentPage - 1) * this.itemsPerPage
      const end = start + this.itemsPerPage
      return this.filteredZonas.slice(start, end);
      \
        \
    }
    ,\

        get startEntry() \
    if (this.filteredZonas.length === 0) return 0;
    return (this.currentPage - 1) * this.itemsPerPage + 1;
    \
        \
    ,\

        get endEntry() \
    {
      const end = this.currentPage * this.itemsPerPage
      return Math.min(end, this.filteredZonas.length);
      \
        \
    }
    ,\

        goToPage(page) \
    this.currentPage = page
    window.addActivity(`navegó a la página $\{page\} de zonas`)
    \
    ,

        nextPage() \
    if (this.currentPage < this.totalPages)
    \
    this.currentPage++
    window.addActivity(`avanzó a la página $\{this.currentPage\} de zonas`)
    \
    \
    ,

        previousPage() \
    if (this.currentPage > 1)
    \
    this.currentPage--
    window.addActivity(`retrocedió a la página $\{this.currentPage\} de zonas`)
    \
    \
    ,

        openCreateModal() \
    this.newZona = \
    nombre: "", sector
    : '' \
    this.isCreateModalOpen = true
    window.addActivity("abrió modal de creación de zona")
    \
    ,

        async createZona() \
    try
    \
    {
      const response = await fetch('/zonas', \{
                    method: 'POST',
                    headers: \{
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    \},
                    body: JSON.stringify(this.newZona)
                \})

      if (!response.ok)
      \
      {
        const errorData = await response.json()
        throw new Error(errorData.message || "Error al crear la zona.")
        \
      }

      const data = await response.json()
      const newId = this.allZonas.length > 0 ? Math.max(...this.allZonas.map((z) => z.id)) + 1 : 1
      const today = new Date().toISOString().split("T")[0]
      this.allZonas.push(\{
                    id: newId,
                    nombre: this.newZona.nombre,
                    sector: this.newZona.sector,
                    ordenesActivas: 0, // New zones start with 0 active orders
                    fechaCreacion: today
                \})
      this.totalZonas = this.allZonas.length // Update total count
      this.filterZonas() // Re-filter and update pagination
      this.isCreateModalOpen = false
      window.addActivity(`creó la zona: $\{this.newZona.nombre\}`)
      alert("Zona creada exitosamente!")
      \
    }
    catch (error) \
    console.error("Error creating zone:", error)
    alert("Error al crear la zona: " + error.message)
    \
    \
    ,

        openEditModal(zona) \
    this.editZona = \
    ...zona \
    this.isEditModalOpen = true
    window.addActivity(`abrió modal de edición para zona: $\{zona.nombre\}`)
    \
    ,

        async saveEditZona() \
    try
    \
    {
      const response = await fetch(`/zonas/$\{this.editZona.id\}`, \{
                    method: 'PUT',
                    headers: \{
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    \},
                    body: JSON.stringify(this.editZona)
                \})

      if (!response.ok)
      \
      {
        const errorData = await response.json()
        throw new Error(errorData.message || "Error al actualizar la zona.")
        \
      }

      const data = await response.json()
      const index = this.allZonas.findIndex((z) => z.id === this.editZona.id)
      if (index !== -1)
      \
      this.allZonas[index] = \
      ...this.editZona \
      \
      this.filterZonas() // Re-filter and update pagination
      this.isEditModalOpen = false
      window.addActivity(`guardó cambios para zona: $\{this.editZona.nombre\}`)
      alert("Zona actualizada exitosamente!")
      \
    }
    catch (error) \
    console.error("Error updating zone:", error)
    alert("Error al actualizar la zona: " + error.message)
    \
    \
    ,

        openDeleteModal(zona) \
    this.deleteZona = zona
    this.isDeleteModalOpen = true
    window.addActivity(`abrió modal de eliminación para zona: $\{zona.nombre\}`)
    \
    ,

        async confirmDeleteZona() \
    try
    \
    {
      const response = await fetch(`/zonas/$\{this.deleteZona.id\}`, \{
                    method: 'DELETE',
                    headers: \{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    \}
                \})

      if (!response.ok)
      \
      {
        const errorData = await response.json()
        throw new Error(errorData.message || "Error al eliminar la zona.")
        \
      }

      const data = await response.json()
      this.allZonas = this.allZonas.filter((zona) => zona.id !== this.deleteZona.id)
      this.totalZonas = this.allZonas.length // Update total count
      this.filterZonas() // Re-filter and update pagination
      this.isDeleteModalOpen = false
      window.addActivity(`eliminó la zona: $\{this.deleteZona.nombre\}`)
      alert("Zona eliminada exitosamente!")
      this.deleteZona = null
      \
    }
    catch (error) \
    console.error("Error deleting zone:", error)
    alert("Error al eliminar la zona: " + error.message)
    \
    \
    ,

        exportZonas() \
    {
      let csvContent = "data:text/csv;charset=utf-8,"
      csvContent += "Nombre,Sector,Ordenes Activas,Fecha Creacion\n" // CSV Header

      this.filteredZonas.forEach(zona => \{
                const row = `"$\{zona.nombre\}","$\{zona.sector\}",$\{zona.ordenesActivas\},"$\{zona.fechaCreacion\}"`;
      csvContent += row + "\n"
      \
    }
    )

    const encodedUri = encodeURI(csvContent)
    const link = document.createElement("a")
    link.setAttribute("href", encodedUri)
    link.setAttribute("download", "zonas.csv")
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    window.addActivity("exportó la lista de zonas a CSV")
    \
  }
  \
}
))
\})
