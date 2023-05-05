import { useState, useEffect } from "react";

const BackendData: React.FC = () => {
  const [schoolName, setSchoolName] = useState<string>("");

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch("http://localhost:8000");
        const data = await response.json();

        console.log(data);
        // setSchoolName(data.data.name);
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    };

    fetchData();
  }, []);

  return (
    <div>
      {schoolName ? (
        <p>School name: {schoolName}</p>
      ) : (
        <p>Loading school name...</p>
      )}
    </div>
  );
};

export default BackendData;
