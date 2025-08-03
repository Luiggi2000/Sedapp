import "./bootstrap"
import Alpine from "alpinejs"
import persist from "@alpinejs/persist"

// Import all Alpine.js components
import "./usuarios.js"
import "./roles.js"
import "./ordenes.js"
import "./evidencias.js"
import "./zonas.js"
import "./dashboard.js"

Alpine.plugin(persist)

// Global activity tracking
window.activities = JSON.parse(localStorage.getItem("activities") || "[]")

window.addActivity = (action) => {
  const activity = {
    id: Date.now(),
    user: "Henry Sagastegui", // In real app, get from auth
    action: action,
    time: new Date().toLocaleString("es-ES", {
      hour: "2-digit",
      minute: "2-digit",
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    }),
    timestamp: Date.now(),
  }

  window.activities.unshift(activity)

  // Keep only last 50 activities
  if (window.activities.length > 50) {
    window.activities = window.activities.slice(0, 50)
  }

  localStorage.setItem("activities", JSON.stringify(window.activities))
  updateActivityBadge()
}

window.openActivityModal = () => {
  const modal = document.getElementById("activity-modal")
  const activityList = document.getElementById("activity-list")

  // Clear existing content
  activityList.innerHTML = ""

  if (window.activities.length === 0) {
    activityList.innerHTML = '<p class="text-gray-500 text-center">No hay actividad reciente</p>'
  } else {
    window.activities.forEach((activity) => {
      const activityElement = document.createElement("div")
      activityElement.className = "flex items-start space-x-3 p-3 bg-gray-50 rounded-lg"
      activityElement.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">${activity.user}</p>
                    <p class="text-sm text-gray-500">${activity.action}</p>
                    <p class="text-xs text-gray-400">${activity.time}</p>
                </div>
            `
      activityList.appendChild(activityElement)
    })
  }

  modal.classList.remove("hidden")
  // Hide badge when modal is opened
  const badge = document.getElementById("activity-badge")
  if (badge) {
    badge.style.display = "none"
  }
}

window.closeActivityModal = () => {
  const modal = document.getElementById("activity-modal")
  modal.classList.add("hidden")
}

window.clearActivity = () => {
  window.activities = []
  localStorage.removeItem("activities")
  window.closeActivityModal()
  updateActivityBadge()
}

function updateActivityBadge() {
  const badge = document.getElementById("activity-badge")
  if (badge) {
    if (window.activities.length > 0) {
      badge.style.display = "block"
    } else {
      badge.style.display = "none"
    }
  }
}

// Initialize badge on page load
document.addEventListener("DOMContentLoaded", () => {
  updateActivityBadge()
})

window.Alpine = Alpine
Alpine.start()
