@extends('layouts.app')

@section('title', 'Mensajería')

@section('content')
<div class="p-6" x-data="mensajeriaData()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: #023A91">Sistema de Mensajería</h1>
            <p class="text-gray-600">Comunicación interna del equipo</p>
        </div>
        <button @click="openNewConversationModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Conversación
        </button>
    </div>

    @if(isset($error))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <div class="flex">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $error }}</span>
        </div>
    </div>
    @endif

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
                    <p class="text-sm font-medium text-gray-600">Conversaciones</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="conversaciones.length"></p>
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
                    <p class="text-sm font-medium text-gray-600">Activas</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getActiveConversations().length"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">No Leídos</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getUnreadCount()"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Participantes</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getTotalParticipants()"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Chat Interface -->
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
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                    >
                </div>
                
                <!-- Conversations -->
                <div class="flex-1 overflow-y-auto">
                    <template x-for="conversation in filteredConversations" :key="conversation.id">
                        <div @click="selectConversation(conversation)" 
                             class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer transition-colors"
                             :class="selectedConversation?.id === conversation.id ? 'bg-blue-50 border-blue-200' : ''">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 truncate" x-text="conversation.title"></h4>
                                    <p class="text-xs text-gray-500 mt-1" x-text="conversation.participants?.join(', ') || 'Sin participantes'"></p>
                                    <p class="text-xs text-gray-400 mt-1" x-text="conversation.last_message || 'Sin mensajes'"></p>
                                </div>
                                <div class="flex flex-col items-end ml-2">
                                    <span class="text-xs text-gray-400" x-text="formatDate(conversation.updated_at)"></span>
                                    <span x-show="conversation.unread_count > 0" 
                                          class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full mt-1"
                                          x-text="conversation.unread_count"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Empty State -->
                    <div x-show="filteredConversations.length === 0" class="p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-sm">No hay conversaciones</p>
                    </div>
                </div>
            </div>
            
            <!-- Chat Area -->
            <div class="flex-1 flex flex-col">
                <!-- Chat Header -->
                <div x-show="selectedConversation" class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900" x-text="selectedConversation?.title"></h3>
                            <p class="text-sm text-gray-500" x-text="selectedConversation?.participants?.join(', ')"></p>
                        </div>
                        <button @click="confirmDeleteConversation(selectedConversation)" 
                                class="text-red-600 hover:text-red-900 p-2 rounded hover:bg-red-50 transition-colors"
                                title="Eliminar conversación">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4" x-ref="messagesContainer">
                    <template x-for="message in messages" :key="message.id">
                        <div class="flex" :class="message.sender_id === {{ auth()->id() }} ? 'justify-end' : 'justify-start'">
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg"
                                 :class="message.sender_id === {{ auth()->id() }} ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900'">
                                <p class="text-sm" x-text="message.content"></p>
                                <p class="text-xs mt-1 opacity-75" x-text="formatTime(message.created_at)"></p>
                            </div>
                        </div>
                    </template>
                    
                    <!-- No messages -->
                    <div x-show="selectedConversation && messages.length === 0" class="text-center text-gray-500 py-8">
                        <p>No hay mensajes en esta conversación</p>
                    </div>
                </div>
                
                <!-- Message Input -->
                <div x-show="selectedConversation" class="p-4 border-t border-gray-200">
                    <form @submit.prevent="sendMessage()" class="flex space-x-2">
                        <input 
                            type="text" 
                            x-model="newMessage" 
                            placeholder="Escribe tu mensaje..." 
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
                
                <!-- No Conversation Selected -->
                <div x-show="!selectedConversation" class="flex-1 flex items-center justify-center text-gray-500">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-lg">Selecciona una conversación para comenzar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Conversation Modal -->
    <div x-show="showNewConversationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Nueva Conversación</h3>
                    <button @click="closeNewConversationModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="createConversation()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Título <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="conversationForm.title" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Participantes <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-2">
                            <template x-for="usuario in usuarios" :key="usuario.id">
                                <label x-show="usuario.id !== {{ auth()->id() }}" class="flex items-center space-x-2 p-1 hover:bg-gray-50 rounded cursor-pointer">
                                    <input type="checkbox" :value="usuario.id" x-model="conversationForm.participant_ids" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm text-gray-700" x-text="usuario.name + ' (' + usuario.email + ')'"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeNewConversationModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
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
                        <span class="font-medium" x-text="conversationToDelete?.title"></span>?
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
        conversaciones: @json($conversaciones ?? []),
        usuarios: @json($usuarios),
        filteredConversations: [],
        selectedConversation: null,
        messages: [],
        searchTerm: '',
        newMessage: '',
        showNewConversationModal: false,
        showDeleteModal: false,
        conversationToDelete: null,
        conversationForm: {
            title: '',
            participant_ids: []
        },

        init() {
            this.filterConversations();
            // Auto-refresh messages every 5 seconds
            setInterval(() => {
                if (this.selectedConversation) {
                    this.loadMessages(this.selectedConversation.id);
                }
            }, 5000);
        },

        filterConversations() {
            this.filteredConversations = this.conversaciones.filter(conv => {
                return conv.title.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                       (conv.participants && conv.participants.some(p => p.toLowerCase().includes(this.searchTerm.toLowerCase())));
            });
        },

        getActiveConversations() {
            return this.conversaciones.filter(conv => conv.status === 'active');
        },

        getUnreadCount() {
            return this.conversaciones.reduce((total, conv) => total + (conv.unread_count || 0), 0);
        },

        getTotalParticipants() {
            const allParticipants = new Set();
            this.conversaciones.forEach(conv => {
                if (conv.participants) {
                    conv.participants.forEach(p => allParticipants.add(p));
                }
            });
            return allParticipants.size;
        },

        async selectConversation(conversation) {
            this.selectedConversation = conversation;
            await this.loadMessages(conversation.id);
        },

        async loadMessages(conversationId) {
            try {
                const response = await fetch(`{{ url('mensajeria') }}/${conversationId}/messages`);
                const data = await response.json();
                
                if (response.ok) {
                    this.messages = data.data || [];
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                }
            } catch (error) {
                console.error('Error al cargar mensajes:', error);
            }
        },

        async sendMessage() {
            if (!this.newMessage.trim() || !this.selectedConversation) return;

            try {
                const response = await fetch('{{ route('mensajeria.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        conversation_id: this.selectedConversation.id,
                        message: this.newMessage,
                        recipient_id: this.selectedConversation.participant_ids?.[0] || 1
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    this.messages.push(data.message);
                    this.newMessage = '';
                    window.addActivity(`envió mensaje en conversación ${this.selectedConversation.title}`);
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                } else {
                    alert('Error al enviar mensaje: ' + (data.error || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        openNewConversationModal() {
            this.conversationForm = { title: '', participant_ids: [] };
            this.showNewConversationModal = true;
        },

        closeNewConversationModal() {
            this.showNewConversationModal = false;
        },

        async createConversation() {
            if (!this.conversationForm.title.trim() || this.conversationForm.participant_ids.length === 0) {
                alert('Por favor, complete todos los campos requeridos.');
                return;
            }

            try {
                const response = await fetch('{{ route('mensajeria.create') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.conversationForm)
                });

                const data = await response.json();

                if (response.ok) {
                    this.conversaciones.push(data.conversation);
                    this.filterConversations();
                    this.closeNewConversationModal();
                    window.addActivity(`creó conversación ${data.conversation.title}`);
                    alert('Conversación creada exitosamente');
                } else {
                    alert('Error: ' + (data.error || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        confirmDeleteConversation(conversation) {
            this.conversationToDelete = conversation;
            this.showDeleteModal = true;
        },

        async deleteConversation() {
            try {
                const response = await fetch(`{{ url('mensajeria') }}/${this.conversationToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    this.conversaciones = this.conversaciones.filter(c => c.id !== this.conversationToDelete.id);
                    this.filterConversations();
                    if (this.selectedConversation?.id === this.conversationToDelete.id) {
                        this.selectedConversation = null;
                        this.messages = [];
                    }
                    this.showDeleteModal = false;
                    window.addActivity(`eliminó conversación ${this.conversationToDelete.title}`);
                    alert(data.message);
                } else {
                    alert('Error: ' + (data.error || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        scrollToBottom() {
            const container = this.$refs.messagesContainer;
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
        },

        formatTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        }
    }));
});
</script>
@endsection
