
import React, { useState } from 'react';
import { TextField, Button, Paper, Typography, Box } from '@mui/material';
import { useAuth } from '../../context/AuthContext';
import api from '../../services/api';
import Swal from 'sweetalert2';

const LoginForm = () => {
  const [username, setUsername] = useState('ncornejo');
  const [password, setPassword] = useState('Root93...');
  const [error, setError] = useState('');
  const { login } = useAuth();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    try {
      const response = await api.post('/login', { username, password });
      if (response.data.state) {
        login(response.data.data.user, response.data.data.token);
        Swal.fire({
          icon: 'success',
          title: '¡Bienvenido!',
          text: 'Login exitoso',
          timer: 1200,
          showConfirmButton: false
        }).then(() => {
          window.location.href = '/';
        });
      } else {
        setError(response.data.message);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.data.message || 'Error al iniciar sesión',
        });
      }
    } catch (err) {
      setError('Error de conexión o del servidor');
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: err.response?.data?.message || 'Error de conexión o del servidor',
      });
    }
  };

  return (
    <Box sx={{ minHeight: '100vh', display: 'flex', alignItems: 'center', justifyContent: 'center', backgroundColor: 'background.default' }}>
      <Paper elevation={3} sx={{ p: 4, maxWidth: 400, width: '100%', mx: 2 }}>
        <Typography variant="h5" component="h1" gutterBottom align="center">
          Login
        </Typography>
        <Box component="form" onSubmit={handleSubmit} sx={{ mt: 2 }}>
          <TextField
            fullWidth
            label="Username"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            margin="normal"
            required
            variant="outlined"
          />
          <TextField
            fullWidth
            label="Password"
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            margin="normal"
            required
            variant="outlined"
          />
          {error && (
            <Typography color="error" sx={{ mt: 1 }}>
              {error}
            </Typography>
          )}
          <Button type="submit" fullWidth variant="contained" sx={{ mt: 2, py: 1.5 }}>
            Login
          </Button>
        </Box>
      </Paper>
    </Box>
  );
};

export default LoginForm;