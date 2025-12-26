import React from 'react';
import { Box, Container, Typography, Grid, Card, CardContent, Button, Toolbar } from '@mui/material';
import { Link } from 'react-router-dom';
import Sidebar from '../components/common/Sidebar';
import Header from '../components/common/Header';

const Dashboard = () => {
  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Header />
      <Box sx={{ display: 'flex', flexGrow: 1 }}>
        <Sidebar />
        <Box component="main" sx={{ flexGrow: 1, p: 3, overflow: 'auto' }}>
          <Toolbar />
          <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
            <Typography variant="h3" gutterBottom>
              Dashboard
            </Typography>
            <Grid container spacing={3}>
              <Grid item xs={12} sm={6} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      Users
                    </Typography>
                    <Typography variant="h5" component="h2">
                      10
                    </Typography>
                    <Button component={Link} to="/users" size="small">
                      View
                    </Button>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} sm={6} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      Companies
                    </Typography>
                    <Typography variant="h5" component="h2">
                      5
                    </Typography>
                    <Button component={Link} to="/companies" size="small">
                      View
                    </Button>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} sm={6} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      Stores
                    </Typography>
                    <Typography variant="h5" component="h2">
                      8
                    </Typography>
                    <Button component={Link} to="/stores" size="small">
                      View
                    </Button>
                  </CardContent>
                </Card>
              </Grid>
              <Grid item xs={12} sm={6} md={3}>
                <Card>
                  <CardContent>
                    <Typography color="textSecondary" gutterBottom>
                      Products
                    </Typography>
                    <Typography variant="h5" component="h2">
                      50
                    </Typography>
                    <Button component={Link} to="/products" size="small">
                      View
                    </Button>
                  </CardContent>
                </Card>
              </Grid>
            </Grid>
          </Container>
        </Box>
      </Box>
    </Box>
  );
};

export default Dashboard;