const fs = require('fs')
const axios = require('axios')
require('dotenv').config()

const apiUrl = process.env.VITE_THEME_MANAGER_API_URL
const apiKey = process.env.VITE_THEME_MANAGER_API_KEY
const dataDirectory = 'resources/admin/assets/ts/data'

const fetchData = async () => {
  try {
    const response = await axios.get(apiUrl + 'theme-elements', {
      headers: {
        Authorization: `Bearer ${apiKey}`,
      },
    })

    const data = response.data

    // Create directory if it doesn't exist
    if (!fs.existsSync(dataDirectory)) {
      fs.mkdirSync(dataDirectory)
    }

    // Save data as elements.json
    fs.writeFileSync(`${dataDirectory}/elements.json`, JSON.stringify(data))

    console.log('Data saved successfully as elements.json')
  } catch (error) {
    console.error('Error occurred while fetching data:', error.message)
  }
}

fetchData()
