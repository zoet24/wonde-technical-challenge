import { useState, useEffect } from "react";

import SelectTeacher from "./components/SelectTeacher";

function App() {
  const [data, setData] = useState({ school: {}, teachers: [] });

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch("http://localhost:8000");
        const fetchedData = await response.json();

        setData(fetchedData);
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    };

    fetchData();
  }, []);

  return (
    <div>
      {/* School information */}

      {/* Select teacher dropdown */}
      <SelectTeacher teachers={data.teachers} />
    </div>
  );
}

export default App;
