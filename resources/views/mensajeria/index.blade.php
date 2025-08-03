@extends('layouts.app')

@section('title', 'Mensajería')

@section('content')
<div class="p-6" x-data="mensajeriaData()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: #023A91">Sistema de Mensajería</h1>
            <p class="text-gray-600">Comunicación en tiempo real del equipo</p>
        </div>
        <button @click="openCreateConversationModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Conversación
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Conversaciones</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_conversations"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Conversaciones Activas</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.active_conversations"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Mensajes</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_messages"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Mensajes No Leídos</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.unread_messages"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Interface -->
    <div class="bg-white rounded-lg shadow overflow-hidden" style="height: 600px;">
        <div class="flex h-full">
            <!-- Conversations List -->
            <div class="w-1/3 border-r border-gray-200 flex flex-col">
                <!-- Search -->
                <div class="p-4 border-b border-gray-200">
                    <input 
                        type="text" 
                        x-model="searchTerm" 
                        @input="filterConversations()"
                        placeholder="Buscar conversaciones..." 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                
                <!-- Conversations -->
                <div class="flex-1 overflow-y-auto">
                    <template x-for="conversation in filteredConversations" :key="conversation.id">
                        <div @click="selectConversation(conversation)" 
                             class="p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors"
                             :class="selectedConversation?.id === conversation.id ? 'bg-blue-50 border-blue-200' : ''">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-medium text-gray-900 truncate" x-text="conversation.name"></h3>
                                <span class="text-xs text-gray-500" x-text="formatTime(conversation.last_message_at)"></span>
                            </div>
                            <p class="text-sm text-gray-600 truncate" x-text="conversation.last_message || 'Sin mensajes'"></p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500" x-text="conversation.participants_count + ' participantes'"></span>
                                <span x-show="conversation.unread_count > 0" 
                                      class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
                                      x-text="conversation.unread_count"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col">
                <!-- Chat Header -->
                <div x-show="selectedConversation" class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-semibold text-gray-900" x-text="selectedConversation?.name"></h2>
                            <p class="text-sm text-gray-600" x-text="selectedConversation?.description || 'Sin descripción'"></p>
                        </div>
                        <div class="flex space-x-2">
                            <button @click="markAsRead()" 
                                    class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors"
                                    title="Marcar como leído">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                            <button @click="confirmDeleteConversation()" 
                                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors"
                                    title="Eliminar conversación">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div x-show="selectedConversation" class="flex-1 overflow-y-auto p-4 space-y-4" x-ref="messagesContainer">
                    <template x-for="message in messages" :key="message.id">
                        <div class="flex" :class="message.sender_id === currentUserId ? 'justify-end' : 'justify-start'">
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg"
                                 :class="message.sender_id === currentUserId ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900'">
                                <div x-show="message.sender_id !== currentUserId" class="text-xs font-medium mb-1" x-text="message.sender_name"></div>
                                <div x-text="message.message"></div>
                                <div class="text-xs mt-1 opacity-75" x-text="formatTime(message.created_at)"></div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Message Input -->
                <div x-show="selectedConversation" class="p-4 border-t border-gray-200">
                    <form @submit.prevent="sendMessage()" class="flex space-x-2">
                        <input 
                            type="text" 
                            x-model="newMessage" 
                            placeholder="Escribe un mensaje..." 
                            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            maxlength="1000"
                        >
                        <button type="submit" 
                                :disabled="!newMessage.trim()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Empty State -->
                <div x-show="!selectedConversation" class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Selecciona una conversación</h3>
                        <p class="mt-1 text-sm text-gray-500">Elige una conversación para comenzar a chatear</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Conversation Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Crear Nueva Conversación</h3>
                    <button @click="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="createConversation()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Conversación *</label>
                            <input type="text" x-model="conversationForm.name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                            <textarea x-model="conversationForm.description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Descripción opcional..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Participantes *</label>
                            <div class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                <template x-for="user in availableUsers" :key="user.id">
                                    <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                        <input type="checkbox" 
                                               :value="user.id" 
                                               x-model="conversationForm.participants"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700" x-text="user.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="closeCreateModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Crear Conversación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-2">Confirmar Eliminación</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        ¿Estás seguro de que quieres eliminar la conversación 
                        <span class="font-medium" x-text="selectedConversation?.name"></span>?
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex justify-center gap-3 mt-4">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="deleteConversation()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mensajeriaData', () => ({
        conversaciones: @json($conversaciones),
        stats: @json($stats),
        availableUsers: @json($availableUsers),
        filteredConversations: [],
        selectedConversation: null,
        messages: [],
        searchTerm: '',
        newMessage: '',
        showCreateModal: false,
        showDeleteModal: false,
        currentUserId: {{ auth()->id() }},
        conversationForm: {
            name: '',
            description: '',
            participants: []
        },

        init() {
            this.filterConversations();
            // Simular actualizaciones en tiempo real
            setInterval(() => {
                if (this.selectedConversation) {
                    this.loadMessages(this.selectedConversation.id);
                }
            }, 5000);
        },

        filterConversations() {
            this.filteredConversations = this.conversaciones.filter(conversation => 
                conversation.name.toLowerCase().includes(this.searchTerm.toLowerCase())
            );
        },

        async selectConversation(conversation) {
            this.selectedConversation = conversation;
            await this.loadMessages(conversation.id);
            this.scrollToBottom();
        },

        async loadMessages(conversationId) {
            try {
                const response = await fetch(`/mensajeria/${conversationId}/messages`);
                if (response.ok) {
                    const data = await response.json();
                    this.messages = data.messages || [];
                    this.scrollToBottom();
                }
            } catch (error) {
                console.error('Error al cargar mensajes:', error);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || !this.selectedConversation) return;

            try {
                const response = await fetch('/mensajeria/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        conversation_id: this.selectedConversation.id,
                        message: this.newMessage,
                        type: 'text'
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    this.messages.push(data.data);
                    this.newMessage = '';
                    this.scrollToBottom();
                } else {
                    alert('Error al enviar mensaje');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        async markAsRead() {
            if (!this.selectedConversation) return;

            try {
                const response = await fetch(`/mensajeria/${this.selectedConversation.id}/mark-read`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    this.selectedConversation.unread_count = 0;
                }
            } catch (error) {
                console.error('Error al marcar como leído:', error);
            }
        },

        openCreateConversationModal() {
            this.conversationForm = { name: '', description: '', participants: [] };
            this.showCreateModal = true;
        },

        closeCreateModal() {
            this.showCreateModal = false;
        },

        async createConversation() {
            if (!this.conversationForm.name || this.conversationForm.participants.length === 0) {
                alert('Por favor, completa todos los campos requeridos');
                return;
            }

            try {
                const response = await fetch('/mensajeria/conversations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.conversationForm)
                });

                if (response.ok) {
                    const data = await response.json();
                    this.conversaciones.push(data.conversation);
                    this.filterConversations();
                    this.closeCreateModal();
                    alert('Conversación creada correctamente');
                } else {
                    alert('Error al crear conversación');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        confirmDeleteConversation() {
            this.showDeleteModal = true;
        },

        async deleteConversation() {
            if (!this.selectedConversation) return;

            try {
                const response = await fetch(`/mensajeria/conversations/${this.selectedConversation.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    this.conversaciones = this.conversaciones.filter(c => c.id !== this.selectedConversation.id);
                    this.selectedConversation = null;
                    this.messages = [];
                    this.filterConversations();
                    this.showDeleteModal = false;
                    alert('Conversación eliminada correctamente');
                } else {
                    alert('Error al eliminar conversación');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        formatTime(timestamp) {
            if (!timestamp) return '';
            const date = new Date(timestamp);
            const now = new Date();
            const diffInHours = (now - date) / (1000 * 60 * 60);

            if (diffInHours < 24) {
                return date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
            } else {
                return date.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit' });
            }
        },

        scrollToBottom() {
            this.$nextTick(() => {
                if (this.$refs.messagesContainer) {
                    this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
                }
            });
        }
    }));
});
</script>
@endsection
