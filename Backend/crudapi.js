const express = require('express');
const fs = require('fs');
const path = require('path');
  
const app = express();
const PORT = 50;
const jsonPath = path.join(__dirname, 'companies.json');

app.use(express.json());

function jsonMessage(message) {
    return { message };
}

//Taken from Lab3 reply api
function readData(){
    const data = fs.readFileSync(jsonPath, 'utf-8');
    return JSON.parse(data).companies;
}

//Taken from Lab3 reply api
function writeData(companies){
    fs.writeFileSync(jsonPath, JSON.stringify({ companies }, null, 2));
}

app.get('/companies', (req, res) => {
    res.json(readData());
});

app.get('/companies/:name', (req, res) => {
    const companies = readData();
    const searchCompany = req.params.name;
    const reqCompany = companies.find((company) => company.name.toUpperCase() === searchCompany.toUpperCase());

    if (!reqCompany) {
        return res.status(404).json(jsonMessage(`Company ${searchCompany} not found`));
    }

    res.json(reqCompany);
});

app.put('/companies/:name', (req, res) => {
    const companies = readData();
    const searchCompany = req.params.name;
    const matchIndex = companies.findIndex((company) => company.name.toUpperCase() === searchCompany.toUpperCase());

    if (matchIndex < 0) {
        return res.status(404).json(jsonMessage(`Company ${searchCompany} not found`));
    }

    if (!req.body.location || req.body.location.trim() === '') {
        return res.status(400).json(jsonMessage('Invalid location data'));
    }

    companies[matchIndex] = req.body;
    writeData(companies);
    res.json(jsonMessage(`Company ${searchCompany} updated successfully`));
});

app.post('/companies', (req, res) => {
    const companies = readData();

    if (!req.body.name || !req.body.location || req.body.name.trim() === '' || req.body.location.trim() === '') {
        return res.status(400).json(jsonMessage('Invalid company data'));
    }

    const newCompany = req.body;
    companies.push(newCompany);
    writeData(companies);
    res.status(201).json(jsonMessage(`Company ${newCompany.name} added successfully`));
});

app.delete('/companies/:name', (req, res) => {
    const companies = readData();
    const deleteCompany = req.params.name;
    const filteredCompanies = companies.filter((company) => company.name.toUpperCase() !== deleteCompany.toUpperCase());

    if (filteredCompanies.length === companies.length) {
        return res.status(404).json(jsonMessage(`Company ${deleteCompany} not found`));
    }

    writeData(filteredCompanies);
    res.json(jsonMessage(`Company ${deleteCompany} deleted successfully`));
});

app.listen(PORT, () => {
    console.log("http://localhost:" + PORT);
});