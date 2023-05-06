import { useState, useEffect } from "react";

import SelectTeacher from "./components/SelectTeacher";

type Student = {
  id: string;
  forename: string;
  surname: string;
};

type Class = {
  id: string;
  name: string;
  main_teacher_id: string;
  students: Student[];
};

type Teacher = {
  id: string;
  title: string;
  forename: string;
  surname: string;
  classes: Class[];
};

type School = {
  name: string;
};

type Data = {
  school: School;
  teachers: Teacher[];
};

function App() {
  const [loading, setLoading] = useState(true);
  const [data, setData] = useState<Data>({
    school: { name: "" },
    teachers: [],
  });

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      try {
        const response = await fetch("http://localhost:8000");
        const fetchedData = await response.json();

        console.log(fetchedData);

        setData(fetchedData);
      } catch (error) {
        console.error("Error fetching data:", error);
      }

      setLoading(false);
    };

    fetchData();
  }, []);

  return (
    <>
      {loading ? (
        <div className="flex justify-center items-center h-screen">
          <h1>Loading...</h1>
        </div>
      ) : (
        <main className="p-8 mx-auto max-w-xl">
          {/* School information */}
          <h1 className="text-center mb-4">{data.school.name}</h1>

          {/* Select teacher dropdown */}
          <SelectTeacher teachers={data.teachers} />
        </main>
      )}
    </>
  );
}

export default App;
