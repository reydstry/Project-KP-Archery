/**
 * Admin Modal System - Custom Pop-up for Confirmations & Alerts
 * Usage:
 * - showConfirm(title, message, onConfirm)
 * - showAlert(title, message, type)
 * - showSuccess(message)
 * - showError(message)
 */

// Global modal container
let modalContainer = null;

// Initialize modal container on DOM load
function initModalContainer() {
    if (modalContainer) return;

    modalContainer = document.createElement("div");
    modalContainer.id = "admin-modal-container";
    document.body.appendChild(modalContainer);
}

// Show confirmation modal
window.showConfirm = function (title, message, onConfirm, options = {}) {
    initModalContainer();

    const {
        confirmText = "Confirm",
        cancelText = "Cancel",
        type = "warning", // warning, danger, info, success
        icon = "⚠️",
    } = options;

    const typeColors = {
        warning: "bg-yellow-500",
        danger: "bg-red-500",
        info: "bg-blue-500",
        success: "bg-green-500",
    };

    const bgColor = typeColors[type] || typeColors.warning;

    const modalHTML = `
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 animate-fadeIn" style="background: rgba(0,0,0,0.5);">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform animate-slideUp">
                <!-- Header -->
                <div class="px-6 py-4 ${bgColor} rounded-t-2xl">
                    <div class="flex items-center gap-3 text-white">
                        <span class="text-3xl">${icon}</span>
                        <h3 class="text-xl font-bold">${title}</h3>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6">
                    <p class="text-slate-700 text-base leading-relaxed whitespace-pre-line">${message}</p>
                </div>
                
                <!-- Actions -->
                <div class="px-6 py-4 bg-slate-50 rounded-b-2xl flex gap-3">
                    <button class="flex-1 px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-semibold transition-all" onclick="closeConfirmModal()">
                        ${cancelText}
                    </button>
                    <button class="flex-1 px-4 py-3 ${bgColor} hover:opacity-90 text-white rounded-xl font-semibold transition-all" onclick="confirmAction()">
                        ${confirmText}
                    </button>
                </div>
            </div>
        </div>
    `;

    modalContainer.innerHTML = modalHTML;
    document.body.style.overflow = "hidden";

    // Store callback
    window._confirmCallback = onConfirm;

    // ESC key to cancel
    const escHandler = (e) => {
        if (e.key === "Escape") {
            closeConfirmModal();
            document.removeEventListener("keydown", escHandler);
        }
    };
    document.addEventListener("keydown", escHandler);
};

window.confirmAction = function () {
    if (window._confirmCallback) {
        window._confirmCallback();
    }
    closeConfirmModal();
};

window.closeConfirmModal = function () {
    if (modalContainer) {
        modalContainer.innerHTML = "";
        document.body.style.overflow = "";
    }
    window._confirmCallback = null;
};

// Show alert modal (info, success, error)
window.showAlert = function (title, message, type = "info") {
    initModalContainer();

    const typeConfig = {
        success: { bg: "bg-green-500", icon: "✅", iconBg: "bg-green-100" },
        error: { bg: "bg-red-500", icon: "❌", iconBg: "bg-red-100" },
        warning: { bg: "bg-yellow-500", icon: "⚠️", iconBg: "bg-yellow-100" },
        info: { bg: "bg-blue-500", icon: "ℹ️", iconBg: "bg-blue-100" },
    };

    const config = typeConfig[type] || typeConfig.info;

    const modalHTML = `
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 animate-fadeIn" style="background: rgba(0,0,0,0.5);">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform animate-slideUp">
                <!-- Icon -->
                <div class="pt-8 pb-4 flex justify-center">
                    <div class="${config.iconBg} rounded-full w-20 h-20 flex items-center justify-center">
                        <span class="text-5xl">${config.icon}</span>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="px-6 py-4 text-center">
                    <h3 class="text-2xl font-bold text-slate-900 mb-3">${title}</h3>
                    <p class="text-slate-600 text-base leading-relaxed whitespace-pre-line">${message}</p>
                </div>
                
                <!-- Action -->
                <div class="px-6 py-6">
                    <button class="w-full px-4 py-3 ${config.bg} hover:opacity-90 text-white rounded-xl font-semibold transition-all" onclick="closeAlertModal()">
                        OK
                    </button>
                </div>
            </div>
        </div>
    `;

    modalContainer.innerHTML = modalHTML;
    document.body.style.overflow = "hidden";

    // ESC key or click outside to close
    const closeHandler = (e) => {
        if (
            e.key === "Escape" ||
            e.target.classList.contains("animate-fadeIn")
        ) {
            closeAlertModal();
            document.removeEventListener("keydown", closeHandler);
        }
    };
    document.addEventListener("keydown", closeHandler);
};

window.closeAlertModal = function () {
    if (modalContainer) {
        modalContainer.innerHTML = "";
        document.body.style.overflow = "";
    }
};

// Shorthand functions
window.showSuccess = function (message, title = "Success!") {
    showAlert(title, message, "success");
};

window.showError = function (message, title = "Error") {
    showAlert(title, message, "error");
};

window.showWarning = function (message, title = "Warning") {
    showAlert(title, message, "warning");
};

window.showInfo = function (message, title = "Information") {
    showAlert(title, message, "info");
};

// Add CSS animations
const style = document.createElement("style");
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to { 
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }
    
    .animate-slideUp {
        animation: slideUp 0.3s ease-out;
    }
`;
document.head.appendChild(style);

// Initialize on load
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initModalContainer);
} else {
    initModalContainer();
}
