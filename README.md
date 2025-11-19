# Sistema de Gestión de Asistencia a Elecciones - SUTEPA

Este sistema fue desarrollado para optimizar el control de asistencia de los afiliados durante el proceso electoral del **Sindicato Unido de Trabajadores y Empleados del PAMI (SUTEPA)**.  
Permite validar la presencia de los votantes mediante QR o validación por SMS, registrar asistencia en tiempo real y facilitar la organización del evento desde un panel administrativo centralizado.

## Funcionalidades principales

| Secciones         | Implementada |
| ----------------- | ------------ |
| Dashboard          | ✅ |
| Afiliados          | ✅ |
| Validación por QR  | ✅ |
| Validación por SMS | ✅ |
| Asistencia         | ✅ |
| Usuarios (admin)   | ✅ |

## Tecnologías utilizadas

- **Frontend**: React + TailwindCSS  
- **Backend**: Laravel + MySQL  
- **Autenticación**: Auth0  
- **Notificaciones**: Integración con SMS  
- **QR**: Generación y escaneo dinámico

## Roles disponibles

- **Administrador**: puede escanear QR, gestionar usuarios, ver reportes y estadísticas.  
- **Afiliado**: accede al sistema mediante link con validación, confirma asistencia.

---

> Desarrollado por Nahuel para SUTEPA 🛠️
