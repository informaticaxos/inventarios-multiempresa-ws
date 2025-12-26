import React, { useState, useEffect } from 'react';
import { Box, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper, Button, Typography, Toolbar, Container } from '@mui/material';
import api from '../../services/api';
import Sidebar from '../common/Sidebar';
import Header from '../common/Header';
import UserForm from './UserForm';
import UserEditForm from './UserEditForm';
import Swal from 'sweetalert2';

const UserList = () => {
  const [users, setUsers] = useState([]);
  const [error, setError] = useState('');
  const [openForm, setOpenForm] = useState(false);
  const [editUser, setEditUser] = useState(null);
  const [page, setPage] = useState(1);
  const [limit] = useState(10);
  const [totalPages, setTotalPages] = useState(1);
  const [total, setTotal] = useState(0);


  useEffect(() => {
    fetchUsers(page);
  }, [page]);

  const fetchUsers = async (pageNum = 1) => {
    try {
      let response;
      // Si es la primera página y el backend soporta /users sin paginación, úsalo
      if (pageNum === 1) {
        response = await api.get('/users');
      } else {
        response = await api.get(`/users?page=${pageNum}&limit=${limit}`);
      }
      if (response.data.state) {
        setUsers(response.data.data);
        setTotalPages(response.data.pagination ? response.data.pagination.pages : 1);
        setTotal(response.data.pagination ? response.data.pagination.total : response.data.data.length);
      } else {
        setError(response.data.message);
      }
    } catch (err) {
      setError('Failed to fetch users');
    }
  };

  const handleEdit = (user) => {
    setEditUser(user);
  };

  const handleDelete = async (user) => {
    const result = await Swal.fire({
      title: '¿Eliminar usuario?',
      text: `¿Seguro que deseas eliminar a ${user.username_user}?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    });
    if (result.isConfirmed) {
      try {
        const response = await api.delete(`/users/${user.id_user}`);
        if (response.data.state) {
          Swal.fire('Eliminado', 'Usuario eliminado correctamente', 'success');
          fetchUsers();
        } else {
          Swal.fire('Error', response.data.message, 'error');
        }
      } catch (err) {
        Swal.fire('Error', 'No se pudo eliminar el usuario', 'error');
      }
    }
  };

  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Header />
      <Box sx={{ display: 'flex', flexGrow: 1 }}>
        <Sidebar />
        <Box component="main" sx={{ flexGrow: 1, p: 3, overflow: 'auto' }}>
          <Toolbar />
          <Box sx={{ width: '100%', px: 0 }}>
            <Typography variant="h4" gutterBottom>
              Users
            </Typography>
            <Button variant="contained" color="primary" sx={{ mb: 2 }} onClick={() => setOpenForm(true)}>
              Crear Usuario
            </Button>
            <UserForm open={openForm} onClose={() => setOpenForm(false)} onUserCreated={fetchUsers} />
            {editUser && (
              <UserEditForm
                open={!!editUser}
                onClose={() => setEditUser(null)}
                user={editUser}
                onUserUpdated={() => {
                  setEditUser(null);
                  fetchUsers();
                }}
              />
            )}
            {error && <Typography color="error">{error}</Typography>}
            <TableContainer component={Paper} sx={{ width: '100%', boxShadow: 3, borderRadius: 2, mb: 2, ml: 0 }}>
              <Table size="medium" sx={{ minWidth: 700 }}>
                <TableHead>
                  <TableRow>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 80 }}>ID</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 160 }}>DNI</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 180 }}>Usuario</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 240 }}>Email</TableCell>
                    <TableCell align="center" sx={{ fontWeight: 'bold', width: 180 }}>Acciones</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {users.length === 0 ? (
                    <TableRow>
                      <TableCell colSpan={5} align="center">No hay usuarios para mostrar.</TableCell>
                    </TableRow>
                  ) : (
                    users.map((user) => (
                      <TableRow key={user.id_user} hover>
                        <TableCell align="center">{user.id_user}</TableCell>
                        <TableCell align="center">{user.dni_user}</TableCell>
                        <TableCell align="center">{user.username_user}</TableCell>
                        <TableCell align="center">{user.email_user}</TableCell>
                        <TableCell align="center">
                          <Button size="small" variant="outlined" sx={{ mr: 1 }} onClick={() => handleEdit(user)}>Editar</Button>
                          <Button size="small" variant="outlined" color="error" onClick={() => handleDelete(user)}>Eliminar</Button>
                        </TableCell>
                      </TableRow>
                    ))
                  )}
                </TableBody>
              </Table>
            </TableContainer>
            {/* Paginación */}
            <Box sx={{ display: 'flex', justifyContent: 'center', alignItems: 'center', mt: 2 }}>
              <Button
                variant="outlined"
                onClick={() => setPage((prev) => Math.max(prev - 1, 1))}
                disabled={page === 1}
                sx={{ mr: 1 }}
              >
                Anterior
              </Button>
              <Typography sx={{ mx: 2 }}>
                Página {page} de {totalPages} ({total} usuarios)
              </Typography>
              <Button
                variant="outlined"
                onClick={() => setPage((prev) => Math.min(prev + 1, totalPages))}
                disabled={page === totalPages}
                sx={{ ml: 1 }}
              >
                Siguiente
              </Button>
            </Box>
          </Box>
        </Box>
      </Box>
    </Box>
  );
};

export default UserList;