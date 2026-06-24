# Medicon v2 🏥

**Medicon** é uma plataforma abrangente de gestão de saúde e consultas médicas, desenvolvida para ligar doentes a hospitais e médicos. O projeto é composto por uma aplicação web/backend robusta e uma aplicação móvel para acesso facilitado.

## 🛠 Tecnologias Utilizadas

O projeto está dividido em duas componentes principais:

### 1. Backend e Painéis Web (Laravel)
- **Framework:** Laravel 11.x (PHP 8.2)
- **Frontend Web:** Blade, Tailwind CSS, Alpine.js, Vite
- **Autenticação:** Laravel Breeze (para web) e Laravel Sanctum (para API Móvel)
- **Base de Dados:** Migrations preparadas para bases relacionais (MySQL/SQLite/PostgreSQL) via Eloquent ORM.
- **Outros:** `barryvdh/laravel-dompdf` para geração de relatórios e documentos médicos em PDF.

### 2. Aplicação Móvel (Flutter)
- **Framework:** Flutter (Dart SDK ^3.12.2)
- **Gestão de Estado:** Provider
- **Integrações:** Firebase Core & Cloud Messaging (FCM) para notificações push em tempo real.
- **Funcionalidades Locais:** `shared_preferences` (cache local), `image_picker` (upload de fotos/avatares), `pdf` e `printing` (visualização e impressão de relatórios médicos).

---

## 🚀 Estado do Projeto (Atualizado a 24 de Junho de 2026)

O desenvolvimento tem avançado significativamente com foco na implementação do core de agendamentos e gestão hospitalar. 

### Funcionalidades Implementadas (Backend & API)
- [x] **Autenticação e Gestão de Utilizadores:** Registo, login e gestão de perfis com suporte a avatares customizados.
- [x] **Gestão Hospitalar:** Operações CRUD de hospitais, suporte para galerias de imagens associadas e configuração de horários de funcionamento (`HospitalSchedules`).
- [x] **Especialidades Médicas:** Categorização e associação de hospitais a diversas especialidades médicas (`Hospital_Specialty`).
- [x] **Agendamento de Consultas (`Appointments`):** Sistema central robusto para marcar e gerir consultas entre pacientes e hospitais.
- [x] **Processo Clínico (`Medical Records`):** Armazenamento seguro de histórico de consultas e dados vitais do paciente.
- [x] **Sistema de Avaliações (`Reviews`):** Os pacientes podem deixar comentários e avaliações das consultas, com suporte para respostas oficiais por parte dos hospitais.
- [x] **Notificações Push:** Integração estruturada com Firebase (FCM Tokens associados aos utilizadores) para alertas e lembretes de consultas.
- [x] **Comunicação:** Sistema de envio de mensagens de contacto.
- [x] **APIs REST:** Endpoints dedicados em `routes/api.php` concebidos para consumo pela aplicação móvel, utilizando Sanctum para autenticação stateless.

### Aplicação Móvel (`custom_medicon`)
- A estrutura base encontra-se configurada com as integrações principais (HTTP, Firebase, Provider).
- Preparada para suporte multiplataforma nativo (Android, iOS).
- Integra ferramentas para partilha (`share_plus`) e gestão de PDFs.

---

## 📂 Estrutura do Repositório

- `/app` - Contém a lógica de negócio do Backend (Models, e vários Controllers organizados como `Admin`, `Api`, `Hospital` - ex: `AppointmentController`, `HospitalController`, `ProfileController`).
- `/custom_medicon` - Código fonte completo da aplicação móvel em Flutter (`/lib`, `pubspec.yaml`).
- `/database/migrations` - Histórico de migrações da base de dados que estabelecem a estrutura de dados relacional do Medicon.
- `/routes` - Definições das rotas da aplicação (`web.php` para os painéis de administração e frontend, `api.php` para uso da App móvel).

---

## 💻 Como Iniciar o Projeto Localmente

### Backend (Laravel)
1. Instalar as dependências do PHP: `composer install`
2. Instalar as dependências do Node.js: `npm install`
3. Copiar e configurar o `.env`: `cp .env.example .env` (e ajustar credenciais de BD)
4. Gerar a chave da aplicação: `php artisan key:generate`
5. Correr as migrações: `php artisan migrate`
6. Iniciar os servidores de desenvolvimento: `composer dev` (comando pré-configurado que corre o PHP server, Vite, filas e logs em simultâneo).

### Mobile App (Flutter)
1. Navegar para a pasta da aplicação móvel: `cd custom_medicon`
2. Obter as dependências do Dart: `flutter pub get`
3. Executar o projeto num emulador ou dispositivo físico: `flutter run`
