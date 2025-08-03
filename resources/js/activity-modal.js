// Activity tracking system (client-side, using localStorage as in original React app)
window.activityLog = []

window.addActivity = (description) => {
  const now = new Date()
  const timestamp = now.toLocaleString("es-ES", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  })
  const activity = {
    id: Date.now(), // Unique ID for activity
    description: description,
    timestamp: timestamp,
    date: now.toISOString().split("T")[0], // YYYY-MM-DD for filtering
  }
  window.activityLog.unshift(activity) // Add to the beginning

  // Keep only the last 100 activities to prevent memory issues
  if (window.activityLog.length > 100) {
    window.activityLog = window.activityLog.slice(0, 100)
  }

  window.updateRecentActivities()
  window.updateActivityModal()
}

window.getRecentActivities = () => {
  return window.activityLog
}

// Render recent activities in the sidebar
function renderRecentActivities() {
  const activitiesList = document.getElementById("recent-activities-list")
  if (!activitiesList) return

  const recentActivities = window.getRecentActivities()
  activitiesList.innerHTML = "" // Clear existing list

  if (recentActivities.length > 0) {
    recentActivities.slice(0, 6).forEach((activity, index) => {
      const activityDiv = document.createElement("div")
      activityDiv.className =
        "bg-white/70 backdrop-blur-sm rounded-md p-2 border border-blue-100 hover:bg-white/90 transition-colors"
      const timestamp = new Date(activity.timestamp)
      const timeString = timestamp.toLocaleTimeString("es-ES", { hour: "2-digit", minute: "2-digit" })
      const userInitials = activity.user
        .split(" ")
        .map((n) => n[0])
        .join("")

      activityDiv.innerHTML = `
                <div class="flex items-start space-x-2">
                    <div class="w-5 h-5 rounded-full bg-[#023A91] flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="text-white text-xs font-medium">${userInitials}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-800 leading-tight">
                            <span class="font-medium">${activity.user}</span>
                            <span class="text-gray-600">${activity.action}</span>
                        </p>
                        <div class="flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-3 h-3 text-gray-400 mr-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span class="text-xs text-gray-500">${timeString}</span>
                        </div>
                    </div>
                </div>
            `
      activitiesList.appendChild(activityDiv)
    })
  } else {
    activitiesList.innerHTML = `
            <div class="text-center py-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-gray-400 mx-auto mb-2"><path d="M22 12A10 10 0 1 1 12 2v10Z"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
                <p class="text-xs text-gray-500">No hay actividades recientes</p>
            </div>
        `
  }
}

// Modal functions
window.openActivityModal = () => {
  const modal = document.getElementById("activity-modal")
  if (modal) {
    modal.style.display = "flex"
    // Trigger Alpine.js x-show transition
    modal.__alpine_scope.open = true
    window.updateActivityModal()
    window.addActivity("abrió el historial de actividades")
  }
}

window.closeActivityModal = () => {
  const modal = document.getElementById("activity-modal")
  if (modal) {
    // Trigger Alpine.js x-show transition
    modal.__alpine_scope.open = false
    setTimeout(() => {
      modal.style.display = "none"
    }, 200) // Match transition duration
    window.addActivity("cerró el historial de actividades")
  }
}

window.updateActivityModal = () => {
  const activityModalList = document.getElementById("activity-modal-list")
  const totalActivitiesCount = document.getElementById("total-activities-count")
  const dateRange = document.getElementById("activity-date-range")?.value || "all"
  const searchTerm = document.getElementById("activity-search-term")?.value.toLowerCase() || ""

  if (activityModalList) {
    activityModalList.innerHTML = ""
    let filtered = window.activityLog

    // Filter by date range
    const now = new Date()
    if (dateRange === "today") {
      const today = now.toISOString().split("T")[0]
      filtered = filtered.filter((activity) => activity.date === today)
    } else if (dateRange === "week") {
      const oneWeekAgo = new Date(now.setDate(now.getDate() - 7)).toISOString().split("T")[0]
      filtered = filtered.filter((activity) => activity.date >= oneWeekAgo)
    } else if (dateRange === "month") {
      const oneMonthAgo = new Date(now.setMonth(now.getMonth() - 1)).toISOString().split("T")[0]
      filtered = filtered.filter((activity) => activity.date >= oneMonthAgo)
    }

    // Filter by search term
    if (searchTerm) {
      filtered = filtered.filter((activity) => activity.description.toLowerCase().includes(searchTerm))
    }

    if (filtered.length === 0) {
      activityModalList.innerHTML =
        '<p class="text-sm text-gray-500 text-center py-12">No se encontraron actividades con los filtros aplicados.</p>'
    } else {
      filtered.forEach((activity) => {
        const div = document.createElement("div")
        div.className = "flex items-start space-x-3 p-3 bg-gray-50 rounded-md border border-gray-200"
        div.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info w-4 h-4 text-blue-500 mt-1"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <div>
                        <p class="text-xs text-gray-500">${activity.timestamp}</p>
                        <p class="text-sm text-gray-800">${activity.description}</p>
                    </div>
                `
        activityModalList.appendChild(div)
      })
    }

    if (totalActivitiesCount) {
      totalActivitiesCount.textContent = filtered.length
    }
  }
}

window.filterActivities = () => {
  window.updateActivityModal()
}

window.exportActivityHistory = () => {
  let csvContent = "data:text/csv;charset=utf-8,"
  csvContent += "Timestamp,Description\n" // CSV Header

  window.activityLog.forEach((activity) => {
    const row = `"${activity.timestamp}","${activity.description.replace(/"/g, '""')}"`
    csvContent += row + "\n"
  })

  const encodedUri = encodeURI(csvContent)
  const link = document.createElement("a")
  link.setAttribute("href", encodedUri)
  link.setAttribute("download", "historial_actividades.csv")
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  window.addActivity("exportó el historial de actividades a CSV")
}

// Initial update when the page loads
document.addEventListener("DOMContentLoaded", () => {
  window.updateRecentActivities()
  window.addActivity("cargó la página") // Log initial page load
})
