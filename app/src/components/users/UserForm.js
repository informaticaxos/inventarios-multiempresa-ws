import React, { useState } from 'react';
import { TextField, Button, Dialog, DialogActions, DialogContent, DialogTitle, Typography } from '@mui/material';
import Swal from 'sweetalert2';
import api from '../../services/api';

const UserForm = ({ open, onClose, onUserCreated }) => {
  const [formData, setFormData] = useState({
    dni_user: '',
    username_user: '',
    email_user: '',
    password_user: ''
  });
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      const response = await api.post('/users', formData);
      if (response.data.state) {
        Swal.fire('Éxito', 'Usuario creado exitosamente', 'success');
        setFormData({
          dni_user: '',
          username_user: '',
          email_user: '',
          password_user: ''
        });
        onUserCreated();
        onClose();
      } else {
        Swal.fire('Error', response.data.message, 'error');
        setError(response.data.message);
      }
    } catch (err) {
      let backendMsg = 'Error al crear usuario';
      if (err.response && err.response.data && err.response.data.message) {
        backendMsg = err.response.data.message;
      }
      Swal.fire('Error', backendMsg, 'error');
      setError(backendMsg);
    }
    setLoading(false);
  };

  return (
    <Dialog open={open} onClose={onClose} maxWidth="sm" fullWidth>
      <DialogTitle>Create New User</DialogTitle>
      <form onSubmit={handleSubmit}>
        <DialogContent>
          <TextField
            autoFocus
            margin="dense"
            name="dni_user"
            label="DNI"
            type="text"
            fullWidth
            variant="outlined"
            value={formData.dni_user}
            onChange={handleChange}
            required
          />
          <TextField
            margin="dense"
            name="username_user"
            label="Username"
            type="text"
            fullWidth
            variant="outlined"
            value={formData.username_user}
            onChange={handleChange}
            required
          />
          <TextField
            margin="dense"
            name="email_user"
            label="Email"
            type="email"
            fullWidth
            variant="outlined"
            value={formData.email_user}
            onChange={handleChange}
            required
          />
          <TextField
            margin="dense"
            name="password_user"
            label="Password"
            type="password"
            fullWidth
            variant="outlined"
            value={formData.password_user}
            onChange={handleChange}
            required
          />
          {/* Los mensajes de error ahora se muestran solo con SweetAlert2 */}
        </DialogContent>
        <DialogActions>
          <Button onClick={onClose}>Cancel</Button>
          <Button type="submit" variant="contained" disabled={loading}>
            {loading ? 'Creating...' : 'Create'}
          </Button>
        </DialogActions>
      </form>
    </Dialog>
  );
};

export default UserForm;