import React, { useState, useEffect } from 'react';
import { Box, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, Paper, Button, Typography, Toolbar, Container } from '@mui/material';
import api from '../../services/api';
import Sidebar from '../common/Sidebar';
import Header from '../common/Header';

const StoreList = () => {
  const [stores, setStores] = useState([]);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchStores();
  }, []);

  const fetchStores = async () => {
    try {
      const response = await api.get('/stores');
      if (response.data.state) {
        setStores(response.data.data);
      } else {
        setError(response.data.message);
      }
    } catch (err) {
      setError('Failed to fetch stores');
    }
  };

  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Header />
      <Box sx={{ display: 'flex', flexGrow: 1 }}>
        <Sidebar />
        <Box component="main" sx={{ flexGrow: 1, p: 3, overflow: 'auto' }}>
          <Toolbar />
          <Container maxWidth="lg">
            <Typography variant="h4" gutterBottom>
              Stores
            </Typography>
            <Button variant="contained" color="primary" sx={{ mb: 2 }}>
              Add Store
            </Button>
            {error && <Typography color="error">{error}</Typography>}
            <TableContainer component={Paper} sx={{ overflowX: 'auto' }}>
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell>ID</TableCell>
                    <TableCell>Name</TableCell>
                    <TableCell>Company</TableCell>
                    <TableCell>Actions</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {stores.map((store) => (
                    <TableRow key={store.id}>
                      <TableCell>{store.id}</TableCell>
                      <TableCell>{store.name}</TableCell>
                      <TableCell>{store.company_name}</TableCell>
                      <TableCell>
                        <Button size="small">Edit</Button>
                        <Button size="small" color="error">Delete</Button>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </TableContainer>
          </Container>
        </Box>
      </Box>
    </Box>
  );
};

export default StoreList;