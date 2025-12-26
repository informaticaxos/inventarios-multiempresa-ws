import React, { useState, useEffect } from 'react';
import { Dialog, DialogTitle, DialogContent, DialogActions, Button, TextField, Box } from '@mui/material';
import api from '../../services/api';
import Swal from 'sweetalert2';

const UserEditForm = ({ open, onClose, user, onUserUpdated }) => {
  const [form, setForm] = useState({ ...user });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  useEffect(() => {
    setForm({ ...user });
  }, [user]);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess('');
    try {
      const response = await api.put(`/users/${user.id_user}`, form);
      if (response.data.state) {
        Swal.fire('Éxito', 'Usuario actualizado exitosamente', 'success');
        onUserUpdated();
        setTimeout(() => {
          setSuccess('');
          onClose();
        }, 1000);
      } else {
        Swal.fire('Error', response.data.message, 'error');
        setError(response.data.message);
      }
    } catch (err) {
      Swal.fire('Error', 'Error al actualizar usuario', 'error');
      setError('Error al actualizar usuario');
    }
    setLoading(false);
  };

  return (
    <Dialog open={open} onClose={onClose} fullWidth maxWidth="sm">
      <DialogTitle>Editar usuario</DialogTitle>
      <DialogContent>
        <Box component="form" onSubmit={handleSubmit} sx={{ mt: 1 }}>
          <TextField
            margin="normal"
            fullWidth
            label="DNI"
            name="dni_user"
            value={form.dni_user || ''}
            onChange={handleChange}
            required
          />
          <TextField
            margin="normal"
            fullWidth
            label="Username"
            name="username_user"
            value={form.username_user || ''}
            onChange={handleChange}
            required
          />
          <TextField
            margin="normal"
            fullWidth
            label="Email"
            name="email_user"
            type="email"
            value={form.email_user || ''}
            onChange={handleChange}
            required
          />
          {/* Password no se edita aquí por seguridad */}
          {/* Mensajes ahora con SweetAlert2 */}
        </Box>
      </DialogContent>
      <DialogActions>
        <Button onClick={onClose} disabled={loading}>Cancelar</Button>
        <Button onClick={handleSubmit} variant="contained" disabled={loading}>Actualizar</Button>
      </DialogActions>
    </Dialog>
  );
};

export default UserEditForm;
