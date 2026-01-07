<p align="center">
	<a href="https://sistema.ghpu.com.br"  target="_blank" title="Sistema de ocorrências para hoteis e pousadas">
		<img src="public/images/brand.png" alt="Sistema de ocorrências para hoteis e pousadas" width="255px">
	</a>
</p>

<br>
<p align="center">
	<img src="https://img.shields.io/badge/version project-1.0-brightgreen" alt="version project">
    <img src="https://img.shields.io/badge/Php-8.3-informational" alt="stack php">
    <img src="https://img.shields.io/badge/Laravel-10.10-informational&color=brightgreen" alt="stack laravel">
    <img src="https://img.shields.io/badge/Livewire-3.5-informational" alt="stack Livewire">
    <img src="https://img.shields.io/badge/TailwindCss-3.4-informational" alt="stack Tailwind">
	<a href="https://opensource.org/licenses/GPL-3.0">
		<img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="GPLv3 License">
	</a>
</p>

# 🏨 Sistema de Ocorrências para Hotéis e Pousadas

Sistema web desenvolvido para **controle, registro e acompanhamento de ocorrências operacionais** em hotéis, pousadas e empreendimentos de hospedagem, com foco em **organização, rastreabilidade e gestão em tempo real**.

---

## ✨ Principais Funcionalidades

### 📋 Registro de Ocorrências
- Ocorrências categorizadas por tipo:
  - **Passagem de Turno**
  - **Ocorrências Diárias**
  - **Varreduras de Fichas x Sistemas**
- Campos dinâmicos por tipo de ocorrência
- Armazenamento estruturado via **JSON**, permitindo flexibilidade e evolução do formulário

---

### 🔄 Passagem de Turno Inteligente
Registro completo da operação do turno, incluindo:
- Número de hóspedes
- Apartamentos ocupados
- Reservas e check-outs
- Controle de chaves, cartões, rádios e equipamentos
- Caixa:
  - 💰 Dinheiro
  - 💳 Cartão
  - 📊 Total consolidado
- Responsáveis e status operacionais

---

### 📊 Relatórios e Indicadores
- Quantidade de ocorrências:
  - Na **semana**
  - No **último mês**
  - No **último ano**
- Gráficos utilizando **Chart.js (AdminLTE)**
- Visualização clara para tomada de decisão

---

### 🕒 Últimas Ocorrências (Dashboard)
- Lista em tempo real com:
  - Avatar do colaborador
  - Tipo e título da ocorrência
  - Data e hora
  - Indicador visual de nova ocorrência
- Atualização automática via **Livewire (`wire:poll`)**

---

### 👥 Controle de Usuários e Permissões
Sistema robusto de permissões baseado em perfis:
- **Super Admin**
- **Admin**
- **Manager**
- **Employee**

Permissões por perfil:
- Visualizar
- Criar
- Editar
- Excluir ocorrências  
- Ações sensíveis protegidas por **Policies (Laravel)**

---

### 📄 Exportação em PDF
- Geração de PDF das ocorrências
- Ideal para:
  - Auditorias
  - Impressão
  - Arquivamento
  - Envio por e-mail

---

### 🔔 Atualizações em Tempo Real
- Notificações visuais para novas ocorrências
- Integração com **Livewire + Alpine.js**
- Feedback imediato para gestores

---

## 🧱 Tecnologias Utilizadas

- **Laravel**
- **Livewire**
- **Tailwind CSS**
- **AdminLTE**
- **Chart.js**
- **Alpine.js**
- **MySQL / MariaDB**
- **SweetAlert2**
- **Carbon**

---

## 🔐 Segurança

- Autorização baseada em Policies
- Controle por empresa (company_id)
- Usuários acessam apenas dados da própria unidade
- Ações críticas protegidas por confirmação

---

## 🚀 Objetivo do Sistema

Centralizar e padronizar o registro das ocorrências operacionais,
reduzindo falhas de comunicação entre turnos e aumentando a confiabilidade das informações para a gestão.

---

## 📦 Instalação

```bash

# Depois que você realizar o clone faça os seguintes comandos
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

```

---

## 📌 Roadmap

- 🔔 Notificações por e-mail
- 📱 Versão mobile (PWA)
- 📈 Relatórios avançados por período
- 📊 Comparativos entre unidades
- 🧾 Assinatura digital na passagem de turno

### 🧑‍💻 Autor

Desenvolvido por <b>Renato Montanari</b>
Sistema voltado para operações reais de hotelaria, com foco em produtividade e clareza.

---

### :sparkles: Colaboradores
<table>
  <tr>
    <td align="center"><a href="https://github.com/informaticalivreoficial">
        <img style="border-radius: 70%;" src="https://avatars.githubusercontent.com/u/28687748?v" width="100px;" alt=""/>
    <br /><sub><b>Renato Montanari</b></sub></a></td>    
  </tr>  
</table>