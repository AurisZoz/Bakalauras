@extends('layout2')

@section('content')
<div class="container p-4" style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="text-secondary">Naudotojų paieška</h5>
                </div>
                <div class="card-body p-0">
                    <input type="text" id="search-users" class="form-control mb-3" placeholder="Ieškoti naudotojų...">
                    <ul id="search-results" class="list-group" style="max-height: 200px; overflow-y: auto;"></ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="text-secondary">Kontaktai</h5>
                </div>
                <div class="card-body p-0">
                    <ul id="contacts-list" class="list-group" style="max-height: 250px; overflow-y: auto;"></ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div id="chat-header" class="mb-3 p-3" style="background-color: #f8f9fa; border-radius: 8px;">
                <h5 id="chat-with" class="m-0 text-primary">Pasirinkite kontaktą</h5>
            </div>
            <div id="chat-box" class="border p-3" style="height: 400px; overflow-y: auto; background-color: #f8f9fa; border-radius: 8px;">
            </div>
            <form id="message-form" class="mt-3">
                @csrf
                <div class="input-group">
                    <input type="text" id="message-input" class="form-control" placeholder="Įveskite žinutę..." disabled>
                    <button type="submit" class="btn btn-primary" disabled>Siųsti</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let activeUser = null;

    document.addEventListener('DOMContentLoaded', () => {
        fetchContacts();
    });

    document.getElementById('search-users').addEventListener('input', function () {
        const query = this.value;
        if (query.length > 0) {
            fetch(`/api/users/search?query=${query}`)
                .then(response => {
                    if (!response.ok) throw new Error(`Serverio klaida: ${response.status}`);
                    return response.json();
                })
                .then(users => {
                    const resultsList = document.getElementById('search-results');
                    resultsList.innerHTML = '';
                    if (users.length === 0) {
                        resultsList.innerHTML = '<li class="list-group-item">Naudotojų nerasta</li>';
                    } else {
                        users.slice(0, 5).forEach(user => {
                            const listItem = document.createElement('li');
                            listItem.className = 'list-group-item';
                            listItem.innerHTML = `
                                <div class="d-flex align-items-center">
                                    <img src="${user.profile_photo || '/img/profileuser.png'}" alt="${user.name}" class="rounded-circle me-2" width="40" height="40">
                                    <div>
                                        <strong>${user.name} ${user.surname}</strong><br>
                                        <small>${user.email}</small>
                                    </div>
                                </div>
                            `;
                            listItem.addEventListener('click', () => addContact(user));
                            resultsList.appendChild(listItem);
                        });
                    }
                })
                .catch(error => {
                    console.error('Klaida ieškant naudotojų:', error);
                    alert('Nepavyko užkrauti naudotojų. Bandykite dar kartą.');
                });
        } else {
            document.getElementById('search-results').innerHTML = '';
        }
    });

    function fetchContacts() {
        fetch('/api/contacts')
            .then(response => {
                if (!response.ok) throw new Error(`Serverio klaida: ${response.status}`);
                return response.json();
            })
            .then(contacts => {
                const contactsList = document.getElementById('contacts-list');
                contactsList.innerHTML = '';
                contacts.forEach(user => {
                    fetch(`/api/messages/${user.id}`)
                        .then(response => response.json())
                        .then(messages => {
                            const unreadCount = messages.filter(msg => !msg.is_read && msg.to_user_id === {{ Auth::id() }}).length;
                            addContactToList(user, unreadCount);
                        });
                });
            })
            .catch(error => {
                console.error('Klaida užkraunant kontaktus:', error);
                alert('Nepavyko užkrauti kontaktų. Bandykite dar kartą.');
            });
    }

    function addContact(user) {
        fetch('/api/contacts/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ contact_id: user.id }),
        })
        .then(response => {
            if (!response.ok) throw new Error(`Serverio klaida: ${response.status}`);
            return response.json();
        })
        .then(() => {
            addContactToList(user);
            const contactItem = document.getElementById(`contact-${user.id}`);
            if (contactItem) {
                setActiveContact(contactItem, user);
            }
        })
        .catch(error => {
            console.error('Klaida pridedant kontaktą:', error);
            alert('Nepavyko pridėti kontakto. Bandykite dar kartą.');
        });
    }

    function addContactToList(user, unreadCount = 0) {
        const contactsList = document.getElementById('contacts-list');
        if (!document.getElementById(`contact-${user.id}`)) {
            const contactItem = document.createElement('li');
            contactItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            contactItem.id = `contact-${user.id}`;
            contactItem.innerHTML = `
    <div class="d-flex align-items-center">
        <img src="${user.profile_photo || '/img/profileuser.png'}" alt="${user.name}" class="rounded-circle me-2" width="40" height="40">
        <div>
            <strong>${user.name} ${user.surname}</strong><br>
            <small>${user.email}</small>
        </div>
    </div>
    ${unreadCount > 0 ? `<span class="badge bg-warning text-dark">${unreadCount}</span>` : ''}
    <button class="btn btn-danger btn-sm" onclick="confirmRemoveContact(${user.id})">
        <i class="fas fa-trash-alt"></i>
    </button>
`;

            contactItem.addEventListener('click', () => setActiveContact(contactItem, user));
            contactsList.appendChild(contactItem);
        }
    }

    function confirmRemoveContact(userId) {
        if (confirm('Ar tikrai norite pašalinti šį kontaktą?')) {
            removeContact(userId);
        }
    }

    function removeContact(userId) {
        fetch(`/api/contacts/remove`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ contact_id: userId }),
        })
        .then(response => {
            if (!response.ok) throw new Error(`Serverio klaida: ${response.status}`);
            return response.json();
        })
        .then(() => {
            const contactItem = document.getElementById(`contact-${userId}`);
            if (contactItem) {
                contactItem.remove();
            }
        })
        .catch(error => {
            console.error('Klaida šalinant kontaktą:', error);
            alert('Nepavyko pašalinti kontakto. Bandykite dar kartą.');
        });
    }

    function setActiveContact(contactItem, user) {
        document.querySelectorAll('#contacts-list .list-group-item').forEach(item => item.classList.remove('active'));
        contactItem.classList.add('active');

        fetch(`/api/messages/${user.id}/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        }).catch(error => console.error('Nepavyko pažymėti žinučių kaip skaitytų:', error));

        loadChat(user);
    }

    function loadChat(user) {
        activeUser = user;
        document.getElementById('chat-with').innerText = `${user.name} ${user.surname}`;
        document.getElementById('message-input').disabled = false;
        document.querySelector('button[type="submit"]').disabled = false;

        fetch(`/api/messages/${user.id}`)
            .then(response => {
                if (!response.ok) throw new Error(`Serverio klaida: ${response.status}`);
                return response.json();
            })
            .then(messages => {
                const chatBox = document.getElementById('chat-box');
                chatBox.innerHTML = '';
                messages.forEach(message => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = `mb-2 ${message.from_user_id === user.id ? 'text-start' : 'text-end'}`;
                    messageDiv.innerHTML = `<span class="badge bg-${message.from_user_id === user.id ? 'secondary' : 'primary'}">${message.content}</span>`;
                    chatBox.appendChild(messageDiv);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(error => {
                console.error('Klaida užkraunant pokalbį:', error);
                alert('Nepavyko užkrauti pokalbio. Bandykite dar kartą.');
            });
    }

    document.getElementById('message-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value;

        if (message.trim() !== '') {
            fetch(`/api/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    content: message,
                    to_user_id: activeUser.id,
                }),
            })
                .then(response => {
                    if (!response.ok) throw new Error(`Serverio klaida: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    messageInput.value = '';
                    loadChat(activeUser);
                })
                .catch(error => {
                    console.error('Klaida siunčiant žinutę:', error);
                    alert('Nepavyko išsiųsti žinutės. Bandykite dar kartą.');
                });
        }
    });
</script>
@endsection
